$('#admin_add_quicklink').on('submit', function(e) {
  e.preventDefault();
  var type = $('#admin_add_quicklink-type').val();
  var title = $('#admin_add_quicklink-title').val();
  var link = $('#admin_add_quicklink-link').val();

  $.ajax({
    type: 'POST',
    url: $url_add_quicklink,
    data: {type:type, title:title, link:link},
    success: function() {
      var toast_heading = "Quick Link Added!";
      var toast_text = "The Quick Link has been added and is now publicly visible! Please refresh the page to edit it!";
      var toast_icon = "success";
      var toast_color = "#f96868";
      globalToast(toast_heading, toast_text, toast_icon, toast_color);

      $.ajax({
        method: "POST",
        url: $url_get_quicklink,
        success: function (response) {
            $('#admin_manage_quicklink').replaceWith(response);
        },
        error: function(data) {
          var toast_heading = "Error!";
          var toast_text = "good job you broke it somehow, oof";
          var toast_icon = "error";
          var toast_color = "#f2a654";
          console.log(data);
          globalToast(toast_heading, toast_text, toast_icon, toast_color);
        }
      });
    },
    error: function(data) {
      var toast_heading = "Error!";
      var toast_text = "good job you broke it somehow";
      var toast_icon = "error";
      var toast_color = "#f2a654";
      globalToast(toast_heading, toast_text, toast_icon, toast_color);
    }
  });
});

$('#admin_manage_quicklink').on('submit', function(e) {
  e.preventDefault();

  // i fucking hate javascript
  let data = {};
  for(var count = 1; count <= $admin_manage_quicklink_count; count++) {
    if ($('#admin_manage_quicklink-type-'+count).length) {
      var type = $('#admin_manage_quicklink-type-'+count).val();
      var title = $('#admin_manage_quicklink-title-'+count).val();
      var link = $('#admin_manage_quicklink-link-'+count).val();
      var id = count;
      data[count] = [type, title, link, id];
    }
  }

  $.ajax({
    type: 'POST',
    url: $url_manage_quicklink,
    data: {data},
    success: function() {
      var toast_heading = "Quick Links Saved!";
      var toast_text = "The Quick Links have been saved and is now publicly visible!";
      var toast_icon = "success";
      var toast_color = "#f96868";
      globalToast(toast_heading, toast_text, toast_icon, toast_color);
    },
    error: function(data) {
      var toast_heading = "Error!";
      var toast_text = "good job you broke it somehow";
      var toast_icon = "error";
      var toast_color = "#f2a654";
      globalToast(toast_heading, toast_text, toast_icon, toast_color);
    }
  });
});