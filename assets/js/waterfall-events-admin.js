
(function () {
   
    /**
     * Ensures that our events select field is also updated with the correct value after saving the event
     * Otherwise, for each save action a new event is created...
     */
    const { getEditedPostAttribute, isSavingPost } = wp.data.select( 'core/editor' );
    let checked = true;

    wp.data.subscribe( () => {
        if ( isSavingPost() ) {
            checked = false;
        } else {
            if( ! checked ) {
                const updatedSyncEvents = getEditedPostAttribute('meta');

                console.log(updatedSyncEvents);

                for( property in updatedSyncEvents ) {

                    if( property.indexOf('wfe_event_sync_target_') === false ) {
                        continue;
                    }

                    let syncEventSelectField = document.getElementById(property);

                    // @todo - if the select field doesn't exist yet, add it

                    if( typeof(syncEventSelectField) !== 'undefined' ) {
                        syncEventSelectField.value = updatedSyncEvents[property];
                        jQuery('#' + property).val(updatedSyncEvents[property]).trigger('change');
                    }

                }
            }
            checked = true;
        }   
    });

})();