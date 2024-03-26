document.addEventListener("DOMContentLoaded", () => {
    var calendarEl = document.getElementById("calendar-holder");
    console.log(new Date)
    var calendar = new FullCalendar.Calendar(calendarEl, {
      selectable: true,
      initialView: 'timeGridWeek',
      editable: true,
      droppable: true, // allow external elements to be dropped onto the calendar
      eventSources: [
        {
          url: "{{ path('load-appointments') }}",
          method: "POST",
          extraParams: {
            filters: JSON.stringify({}),
          },
          failure: () => {
            alert("There was an error while fetching FullCalendar!");
          },
        },
      ],
      eventTimeFormat: {
        // like '14:30:00'
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
      },
      headerToolbar: {
        start: "prev,next today",
        center: "title",
        end: "dayGridMonth,timeGridWeek,timeGridDay",
      },
      dateClick: function (info) {
        if (info.date) {
          var formattedTimestamp = info.date.toISOString();

          $.ajax({
            url: "/new",
            type: "POST",
            contentType: "application/json", // Set the content type to JSON
            data: { timestamp: formattedTimestamp }, // Convert data to JSON string
            success: function (response) {
              window.location.href =
                "/appointments/new?timestamp=" + formattedTimestamp;
            },
            error: function (xhr, status, error) {
              // Handle errors if needed
            },
          });
        }
      },
      // select: function (info) {
      //   alert('selected ' + info.startStr + ' to ' + info.endStr)
      // },
      eventDrop: function (info) {
        if (info) {
          var formattedTimestamp = info.event.start.toISOString();
          var eventId = info.event.id
          $.ajax({
            url: `${eventId}/edit`,
            type: "POST",
            contentType: "application/json", // Set the content type to JSON
            data: { timestamp: formattedTimestamp }, // Convert data to JSON string
            success: function (response) {
              window.location.href = `/appointments/${eventId}/edit?timestamp=` + formattedTimestamp;
            },
            error: function (xhr, status, error) {
              // Handle errors if needed
            },
          });
        }
      },

      // Event Drag Functionality
      eventDragStart: function (info) {
        console.log("Event drag started:", info);
      },
      eventDragStop: function (info) {
        console.log("Event drag stopped:", info);
      },
      timeZone: "UTC",
    });
    
    calendar.render();

    // $(".fc-timeGridWeek-button").click();

  });