export default {
  calendars: {},  
  config: {}, 
  // Initialize our module
  initialize() {

    if( typeof(FullCalendar) == 'undefined' ) {
      return;
    }

    document.querySelectorAll('.wfe-calendar').forEach( (el) => {
            
      if( typeof(window['wfeCalendar' + el.id]) === 'undefined' ) {
          return;
      }
      
      // Unique map configurations, moving the config inside the object
      this.config[el.id] = window['wfeCalendar' + el.id];

      // Set-up main functionalities
      this.calendars[el.id] = this.createCalendar(el);

    });   

  },

  /**
   * Creates the calendar
   * @param {*} el The calendar element
   */
  createCalendar(el) {

    const calendar = new FullCalendar.Calendar(el, {
      headerToolbar: {
        start: 'prev next today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      locale: this.config[el.id].locale ? this.config[el.id].locale : 'en',
      initialView: this.config[el.id].initialView ? this.config[el.id].initialView : 'dayGridMonth',
      events: this.config[el.id].events
    });

    calendar.render();

    return calendar;

  }

};