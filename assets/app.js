// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')

var at = document.documentElement.getAttribute("data-layout");
if ((at = "vertical")) {
  function findMatchingElement() {
    var currentUrl = window.location.href;
    var anchors = document.querySelectorAll("#sidebarnav a");
    for (var i = 0; i < anchors.length; i++) {
      if (anchors[i].href === currentUrl) {
        return anchors[i];
      }
    }

    return null; // Return null if no matching element is found
  }
  var elements = findMatchingElement();

  // Do something with the matching element
  if(elements){
    elements.classList.add("active");
  }

  document
    .querySelectorAll("ul#sidebarnav ul li a.active")
    .forEach(function (link) {
      link.closest("ul").classList.add("in");
      link.closest("ul").parentElement.classList.add("selected");
    });

  document.querySelectorAll("#sidebarnav li").forEach(function (li) {
    const isActive = li.classList.contains("selected");
    if (isActive) {
      const anchor = li.querySelector("a");
      if (anchor) {
        anchor.classList.add("active");
      }
    }
  });
  document.querySelectorAll("#sidebarnav a").forEach(function (link) {
    link.addEventListener("click", function (e) {
      const isActive = this.classList.contains("active");
      const parentUl = this.closest("ul");
      if (!isActive) {
        // hide any open menus and remove all other classes
        parentUl.querySelectorAll("ul").forEach(function (submenu) {
          submenu.classList.remove("in");
        });
        parentUl.querySelectorAll("a").forEach(function (navLink) {
          navLink.classList.remove("active");
        });

        // open our new menu and add the open class
        const submenu = this.nextElementSibling;
        if (submenu) {
          submenu.classList.add("in");
        }

        this.classList.add("active");
      } else {
        this.classList.remove("active");
        parentUl.classList.remove("active");
        const submenu = this.nextElementSibling;
        if (submenu) {
          submenu.classList.remove("in");
        }
      }
    });
  });
}
if ((at = "horizontal")) {
  function findMatchingElement() {
    var currentUrl = window.location.href;
    var anchors = document.querySelectorAll("#sidebarnavh ul#sidebarnav a");
    for (var i = 0; i < anchors.length; i++) {
      if (anchors[i].href === currentUrl) {
        return anchors[i];
      }
    }

    return null; // Return null if no matching element is found
  }
  var elements = findMatchingElement();

  if(elements){
    elements.classList.add("active");
  }
  document
    .querySelectorAll("#sidebarnavh ul#sidebarnav a.active")
    .forEach(function (link) {
      link.closest("a").parentElement.classList.add("selected");
      link.closest("ul").parentElement.classList.add("selected");
    });
}

$(document).ready(function() {
  
  $('#submit-btn').on('click', function(event) {
      // Prevent the default button click behavior
      event.preventDefault();

      // Get the appointment time from the form
      var appointmentTime = $('#form_time_at').val();
      console.log(appointmentTime)

      // Make AJAX request to check for conflicts
      $.ajax({
             url: `/appointments/check-conflict?time_at=${appointmentTime}`,
          method: 'GET',
          success: function(response) {
              console.log(response)
              if (response.conflict == true) {
                  // If conflict is found, show modal
                  $('#conflictModal').modal('show');
              }
              else {
                  // If no conflict, submit the form
                  $('#new-form').submit();
              }
               
          },
          error: function(xhr, status, error) {
              // Handle errors if needed
          }
      });
  });
  
  // Attach click event handler to the "Proceed" button inside the modal
  $('#submitFormModal').on('click', function() {
      // Submit the form
      $('#new-form').submit();
  });

  // Attach click event handler to the "Close" button inside the modal
  $('#closeButtonModal').on('click', function() {
      // Close the modal
      $('#conflictModal').modal('hide');
  });
});