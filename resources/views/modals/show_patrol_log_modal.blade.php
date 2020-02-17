<!-- Open a patrol log - Modal -->
<!-- Open modal with button id #ajax_view_patrol_log_open -->
<div class="modal fade" id="ajax_view_patrol_log" tabindex="-1" role="dialog" aria-labelledby="ajax_view_patrol_log" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Patrol Log</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
      <form id="ajax_edit_patrol_log">
      @endif
        <div class="modal-body">

            <div class="form-group">
              <label>Submitted by:</label>
              <h5 id="ajax-patrol-log-by"></h5>
            </div>

            <div class="form-group">
              <label>Website ID:</label>
              <h5 id="ajax-patrol-website-id"></h5>
            </div>

            <div class="form-group">
              <label>Patrol Type</label>
              <select class="js-example-basic-single" style="width:100%" id="ajax-input-patrol-log-type" name="patrol_type" disabled>
                @foreach($constants['patrol_type'] as $item => $value)
                  <option value="{{ $item }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Patrol Start Date</label>
              <div id="patrol_start_date_datepicker" class="input-group date datepicker">
                <input type="text" class="form-control" id="ajax-input-patrol-log-start-date" disabled>
                <span class="input-group-addoan input-group-append border-left">
                  <span class="mdi mdi-calendar input-group-text"></span>
                </span>
              </div>
              <label id="ajax-input-patrol-log-start-date-error" class="error mt-2 text-danger" for="ajax-input-patrol-log-start-date" hidden></label>
            </div>

            <div class="form-group">
              <label>Patrol End Date</label>
              <div id="patrol_end_date_datepicker" class="input-group date datepicker">
                <input type="text" class="form-control" id="ajax-input-patrol-log-end-date" disabled>
                <span class="input-group-addoan input-group-append border-left">
                  <span class="mdi mdi-calendar input-group-text"></span>
                </span>
              </div>
              <label id="ajax-input-patrol-log-end-date-error" class="error mt-2 text-danger" for="ajax-input-patrol-log-end-date" hidden></label>
            </div>

            <div class="form-group">
              <label>Patrol Start Time</label>
              <div class="input-group date" id="ajax-patrol-start-time" data-target-input="nearest">
                <div class="input-group" data-target="#ajax-patrol-start-time" data-toggle="datetimepicker">
                  <input type="text" class="form-control datetimepicker-input" data-target="#ajax-patrol-start-time" id="ajax-input-patrol-start-time" disabled>
                  <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
                </div>
              </div>
              <label id="ajax-input-patrol-start-time-error" class="error mt-2 text-danger" for="ajax-input-patrol-start-time" hidden></label>
            </div>

            <div class="form-group">
              <label>Patrol End Time</label>
              <div class="input-group date" id="ajax-patrol-end-time" data-target-input="nearest">
                <div class="input-group" data-target="#ajax-patrol-end-time" data-toggle="datetimepicker">
                  <input type="text" class="form-control datetimepicker-input" data-target="#ajax-patrol-end-time" id="ajax-input-patrol-end-time" disabled>
                  <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
                </div>
              </div>
              <label id="ajax-input-patrol-end-time-error" class="error mt-2 text-danger" for="ajax-input-patrol-end-time" hidden></label>
            </div>

            <div class="form-group">
              <label>Total Patrol Time</label>
              <div class="input-group">
                <input type="text" class="form-control" id="ajax-input-patrol-total-time" disabled>
                <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
              </div>
            </div>

            <div class="form-group">
              <label>Patrol Description</label>
              <textarea class="form-control" id="ajax-input-patrol-details" rows="6" disabled></textarea>
              <label id="ajax-input-patrol-details-error" class="error mt-2 text-danger" for="ajax-input-patrol-details" hidden></label>

            </div>

              <div class="form-group">
                  <label>Patrol Area</label>
                  <select class="js-example-basic-multiple" multiple="multiple" id="ajax-input-patrol-area" style="width:100%" disabled>
                      @foreach($constants['patrol_area'] as $patrol_area => $value)
                        <option value="{{$value}}">{{$value}}</option>
                      @endforeach
                </select>
                <label id="ajax-input-patrol-area-error" class="error mt-2 text-danger" for="ajax-input-patrol-area" hidden></label>
              </div>
              
              <div class="form-group">
                  <label>Patrol Priorities</label>
                  <div class="input-group" id="ajax-input-patrol-priorities2" data-target-input="nearest">
                    <input type="number" class="form-control" id="ajax-input-patrol-priorities" disabled>
                    <div class="input-group-addon input-group-append"><i class=" mdi mdi-apple-keyboard-caps input-group-text"></i></div>
                  </div>
                  <label id="ajax-input-patrol-priorities-error" class="error mt-2 text-danger" for="ajax-input-patrol-priorities" hidden></label>
              </div>

              <div class="form-group">
                  <h5>Flags</h5>
                  <label>Self Flag: <span id="ajax-span-self-flag"></span></label>
                  <textarea class="form-control" id="ajax-textarea-self-flag" rows="6" placeholder="No details." disabled></textarea>
                  <br>
                  <label>Automatic Flag: <span id="ajax-span-auto-flag"></span></label>
                  <textarea class="form-control" id="ajax-textarea-auto-flag" rows="6" placeholder="No details." disabled></textarea>
              </div>
          </div>
        <div class="modal-footer">
          @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
          <button type="button" class="btn btn-primary" id="ajax_edit_patrol_log-button">Edit</button>
          <button type="submit" class="btn btn-success" id="ajax_submit_edit_patrol_log-button" value="0" hidden>Submit</button>
          @endif
          <button type="button" class="btn btn-light" data-dismiss="modal" id="ajax_new_patrol_log_cancel">Close</button>
        </div>
      @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
      </form>
      @endif
    </div>
  </div>
</div>

<style>
.select2-search__field {width: 0 !important;}
.select2-selection {background-color:#2A3038 !important;}
</style>
<script type="text/javascript">
  var $url_show_patrol_log = '{{ url('activity/get_data/') }}/';
  @if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
  var $url_edit_patrol_log = '{{ url('activity/edit/') }}/';
  @endif
</script>
<script src="/js/modals/show_patrol_log_modal.js"></script>
@if(Auth::user()->level() >= $constants['access_level']['seniorstaff'])
<script src="/js/modals/edit_patrol_log_modal.js"></script>
@endif