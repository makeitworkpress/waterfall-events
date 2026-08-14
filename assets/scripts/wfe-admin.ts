/**
 * Keeps the multisite event sync select fields in sync with the values that PHP wrote
 * during the save request.
 *
 * Without this, the block editor keeps posting an empty sync target after the first save,
 * which makes Controllers\Events::sync_save_events() create a brand new event on the
 * target site on every single save.
 */

/** The `core/editor` selectors that we rely on. */
interface EditorSelectors {
    getCurrentPostId: () => number;
    getCurrentPostType: () => string;
    getEditedPostAttribute: (attribute: string) => unknown;
    isSavingPost: () => boolean;
}

/** The relevant part of a REST response for a single event. */
interface EventRestResponse {
    meta: Record<string, string>;
}

const SYNC_TARGET_PREFIX = 'wfe_event_sync_target_';

/**
 * Writes the freshly created target post IDs into their select fields, so a subsequent
 * save updates the remote event instead of creating another one.
 *
 * @param meta      The event meta as returned by the REST API.
 * @param postTitle The title to label the newly added option with.
 */
function updateSyncFields(meta: Record<string, string>, postTitle: string): void {
    Object.keys(meta)
        .filter((property) => property.startsWith(SYNC_TARGET_PREFIX))
        .forEach((property) => {
            const field = document.getElementById(property) as HTMLSelectElement | null;

            // The field is absent when the sync for this site is switched off
            if (!field) {
                return;
            }

            // Leave the field alone if it already points at a remote event
            if (field.value) {
                return;
            }

            const targetId = meta[property];

            // First add a new option to the select field, then trigger the value change
            field.innerHTML = `<option value="${targetId}">${postTitle}</option>${field.innerHTML}`;
            field.value = targetId;

            if (typeof jQuery !== 'undefined') {
                jQuery(`#${property}`).val(targetId).trigger('change');
            }
        });
}

/**
 * Subscribes to the editor store and back-fills the sync fields after every save.
 */
function initializeSyncFieldUpdater(): void {
    if (typeof wp === 'undefined' || !wp.data) {
        return;
    }

    const editor = wp.data.select('core/editor') as EditorSelectors;

    if (!editor) {
        return;
    }

    let wasSaving = false;

    wp.data.subscribe(() => {
        // Only applies to events
        if (editor.getCurrentPostType() !== 'events') {
            return;
        }

        if (editor.isSavingPost()) {
            wasSaving = true;
            return;
        }

        if (wasSaving) {
            const postId = editor.getCurrentPostId();
            const postTitle = String(editor.getEditedPostAttribute('title') ?? '');

            // We have to fetch our data somewhat later, because the updated meta is not immediately available.
            setTimeout(() => {
                wp.apiFetch({ path: `wp/v2/events/${postId}` })
                    .then((post: EventRestResponse) => {
                        // Nothing to update
                        if (!post.meta || Object.keys(post.meta).length === 0) {
                            return;
                        }

                        updateSyncFields(post.meta, postTitle);
                    })
                    .catch((error: unknown) => console.error(error));
            }, 1000);
        }

        wasSaving = false;
    });
}

initializeSyncFieldUpdater();
