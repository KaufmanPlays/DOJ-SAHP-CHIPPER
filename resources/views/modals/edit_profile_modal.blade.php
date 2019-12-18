@if(Auth::user()->level() >= $constants['access_level']['sit'])
<!-- Edit a Profile - Modal -->
<!-- Open modal with button id #ajax_open_modal_edit -->

<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
        <form id="ajax_edit_member">
        @endif
          <div class="modal-body">

            <div class="row">
              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h2 class="card-title" id="profile-display-name">ajax-profile-display-name</h2>
                    <h5 class="card-title">General Data</h5>

                    @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" class="form-control p_input" id="profile-name-field" name="name" autocomplete="name" autofocus value="ajax-profile-display-input-name">
                    </div>
                    @else
                    <div class="form-group">
                      <label>Name</label>
                      <input type="text" class="form-control p_input" require id="profile-name-field" name="name" autocomplete="name" disabled value="ajax-profile-display-input-name">
                    </div>
                    @endif

                    @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
                    <div class="form-group">
                      <label>Website ID</label>
                      <input type="text" class="form-control p_input" require id="profile-website-id-field" name="website-id" value="ajax-profile-display-input-website-id">
                    </div>
                    @else
                    <div class="form-group">
                      <label>Website ID</label>
                      <input type="text" class="form-control p_input" disabled id="profile-website-id-field" name="website-id" value="ajax-profile-display-input-website-id">
                    </div>
                    @endif

                    @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
                    <div class="form-group">
                      <label>{{ $constants['department']['department_callsign'] }}</label>
                      <input type="text" class="form-control p_input" require id="profile-department-id-field" name="department-id" value="ajax-profile-display-input-department-id">
                    </div>
                    @else
                    <div class="form-group">
                      <label>{{ $constants['department']['department_callsign'] }}</label>
                      <input type="text" class="form-control p_input" disabled id="profile-department-id-field" name="department-id" value="ajax-profile-display-input-department-id">
                    </div>
                    @endif

                    @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
                    <div class="form-group">
                      <label>Rank</label>
                      <select class="js-example-basic-single" style="width:100%" id="profile-rank-field" name="rank">
                        @foreach($constants['rank'] as $rank => $value)
                          <option value="{{ $rank }}">{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                    @else
                    <div class="form-group">
                      <label>Rank</label>
                      <select class="js-example-basic-single" style="width:100%" id="profile-rank-field" name="rank" disabled>
                        @foreach($constants['rank'] as $rank => $value)
                          <option value="{{ $rank }}">{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                    @endif

                    @if(Auth::user()->level() >= $constants['access_level']['admin'])
                    <div class="form-group">
                      <label>Profile Settings</label>
                      <div class="form-check form-check-success">
                        <label class="form-check-label"><input type="checkbox" class="profile-active-field" id="profile-active-field"> Active Profile <i class="input-helper"></i></label>
                      </div>
                    </div>
                    @else
                    <div class="form-group">
                      <label>Profile Settings</label>
                      <div class="form-check form-check-success">
                        <label class="form-check-label"><input type="checkbox" class="profile-active-field" id="profile-active-field" disabled> Active Profile <i class="input-helper"></i></label>
                      </div>
                    </div>
                    @endif

                  </div>
                </div>
              </div>
              <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                      <h4 class="card-title mb-1">Statistics or something</h4>
                      <p class="text-muted mb-1">Your data status</p>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="preview-list">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <div class="modal-footer">
            @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
            <button type="submit" class="btn btn-success">Edit</button>
            @endif
            <button type="button" class="btn btn-light" data-dismiss="modal" id="cancelEditMember">Cancel</button>
          </div>
      @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
      </form>
      @endif
    </div>
  </div>
</div>

<script type="text/javascript">

  showFailToast_EditSuperAdmin = function() {
    'use strict';
    $.toast({
      heading: 'Profile Protected!',
      text: 'You are not allowed to edit other superadmins.',
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'top-right'
    })
  };

  // TODO: Implement case not on member_admin
  // Future self here: what the fuck did I mean by that?
  $table = $('#tableElement');
  $table.on('click', '#ajax_open_modal_edit', function () {
    var isSuperAdmin = false;
    var id = $(this).val();
      $.ajax({
         type: "POST",
         url: '{{ url('member/edit/get_data/') }}/'+id,
         success: function(data){
            var role = data.roles.map(function(dt) {
              return dt.slug;
            });
           console.log(data);
           console.log(role);
           if (role == "superadmin") {
              return false;
           } else {
             if(data['department_id'] == null) {
                var name_unitnumber = data['name'];
             } else var name_unitnumber = data['name']+' '+data['department_id'];
             $("#profile-display-name").text(name_unitnumber);
             $("#profile-name-field").val(data['name']);
             $("#profile-website-id-field").val(data['website_id']);
             $("#profile-department-id-field").val(data['department_id']);
             $("#profile-rank-field").val(data['rank']).change();
             $("#profile-active-field").prop('checked', data['antelope_status']);
           }
         }
      }).done(function(data) {
        var role = data.roles.map(function(dt) {
          return dt.slug;
        });
        if(role == 'superadmin') {
          showFailToast_EditSuperAdmin();
        } else $("#editProfileModal").modal("toggle");
      });
  });

  showSuccessToast_EditMember = function() {
    'use strict';
    $.toast({
      heading: 'Profile Edited!',
      text: 'This profile has been edited and the new data has been sent to the database!',
      showHideTransition: 'slide',
      icon: 'success',
      loaderBg: '#f96868',
      position: 'top-right'
    })
  };

  showFailToast_EditMember = function() {
    'use strict';
    $.toast({
      heading: 'Profile Edit Failed!',
      text: 'Profile Edit failed, double check the fields to make sure that nothing is missing and that the website ID is not taken.',
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'top-right'
    })
  };

  (function($) {
    'use strict';

    if ($(".js-example-basic-single").length) {
      $(".js-example-basic-single").select2();
    }
    if ($(".js-example-basic-multiple").length) {
      $(".js-example-basic-multiple").select2();
    }
  })(jQuery);
  @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
    $('#ajax_edit_member').on('submit', function(e) {
      e.preventDefault();
      var id = $('#ajax_open_modal_edit').val();
      var name = $('#profile-name-field').val();
      var website_id = $('#profile-website-id-field').val();
      var department_id = $('#profile-department-id-field').val();
      var rank = $('#profile-rank-field').val();
      var antelope_status = $('#profile-active-field').prop('checked');

      $.ajax({
        type: 'POST',
        url: '{{ url('member/edit/edit_user/') }}/'+id,
        data: {name:name, website_id:website_id, department_id:department_id, rank:rank, antelope_status:antelope_status},
        success: function() {
            showSuccessToast_EditMember();
          },
        error: function() {
            showFailToast_EditMember();
          }
      });
    });
  @endif
</script>
@endif