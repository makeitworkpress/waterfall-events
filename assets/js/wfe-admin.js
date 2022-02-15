
   
/**
 * Ensures that our events synchronizing select field is also updated with the correct value after saving the event for the first time
 * Otherwise, for each save action a new event is created and the database gets messed up quickly...
 */
(function () {
    const { getEditedPostAttribute, isSavingPost, getCurrentPostId, getCurrentPostType } = wp.data.select( 'core/editor' );
    let updatedMeta = {};
    let wasSaving   = false;


    wp.data.subscribe( () => {

        // Only applies to events
        if( getCurrentPostType !== 'events' ) {
            return;
        }        

        if( isSavingPost() ) {
            wasSaving = true;
            return;
        } 

        if( wasSaving ) {
            const postId    = getCurrentPostId();
            const postTitle = getEditedPostAttribute('title');

            // We have to fetch our data somewhat later, because the updated meta is not immediately available.
            setTimeout( () => {

                wp.apiFetch({ path: 'wp/v2/events/' + postId })
                    .then( post => {

                        // Nothing to update
                        if( Object.keys(post.meta).length === 0 || updatedMeta === post.meta ) {
                            return;
                        }

                        updatedMeta = post.meta;

                        // Look in our updated meta and change the select field accordingly
                        for( property in updatedMeta ) {
                            
                            // Only consider our sync targets
                            if( property.indexOf('wfe_event_sync_target_') === false ) {
                                continue;
                            }   
                            
                            let syncEventSelectField = document.getElementById(property);

                            // Continue if we already have a value
                            if( syncEventSelectField.value ) {
                                continue;
                            }

                            // First, add a new option to the select field and then trigger the value change
                            syncEventSelectField.innerHTML = '<option value="' + updatedMeta[property] + '">' + postTitle + '</option>' + syncEventSelectField.innerHTML;
                            syncEventSelectField.value = updatedMeta[property];
                            jQuery('#' + property).val(updatedMeta[property]).trigger('change');

                        }
                    })
                    .catch( error => console.log(error) );
            }, 1000);

        }

        wasSaving = false;   
 
    });

})();