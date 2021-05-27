@extends('layouts.app')
@section('style')
    <link href="{{ asset('fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <form method="POST" action="">
                    @csrf
                    <div class="mb-3">
                        <label for="event" class="form-label">Event</label>
                        <input type="text" class="form-control" id="event" name="event" value="{{ $event['event_name'] ?? '' }}" required="required">
                    </div>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="text" class="form-control" id="startDate" name="startDate" value="{{ $event['start_date'] ?? '' }}" required="required">
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="text" class="form-control" id="endDate" name="endDate" value="{{ $event['end_date'] ?? '' }}" {{ (empty($event['end_date']) ? 'disabled' : '') }} required="required">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('monday', $days) ? '1' : '0') }}" id="monday" name="days[monday]">
                        <label class="form-check-label" for="monday">
                            Monday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('tuesday', $days) ? '1' : '0') }}" id="tuesday" name="days[tuesday]">
                        <label class="form-check-label" for="tuesday">
                            Tuesday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('wednesday', $days) ? '1' : '0') }}" id="wednesday" name="days[wednesday]">
                        <label class="form-check-label" for="wednesday">
                            Wednesday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('thursday', $days) ? '1' : '0') }}" id="thursday" name="days[thursday]">
                        <label class="form-check-label" for="thursday">
                            Thursday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('friday', $days) ? '1' : '0') }}" id="friday" name="days[friday]">
                        <label class="form-check-label" for="friday">
                            Friday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('saturday', $days) ? '1' : '0') }}" id="saturday" name="days[saturday]">
                        <label class="form-check-label" for="saturday">
                            Saturday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input days" type="checkbox" value="{{ (in_array('sunday', $days) ? '1' : '0') }}" id="sunday" name="days[sunday]">
                        <label class="form-check-label" for="sunday">
                            Sunday
                        </label>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Add event</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('fullcalendar/fullcalendar.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let availableDays = [];
            let event = <?= json_encode($event) ?>;
            let events = <?= json_encode($events) ?>;
            let days = <?= json_encode($days) ?>;

            let startDate = moment($('#startDate').val(), 'MM/DD/YYYY');
            let endDate = moment($('#endDate').val(), 'MM/DD/YYYY');

            for (var m = moment(startDate); m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
                availableDays.push(m.format('dddd').toLowerCase());
            }

            $('.days').each(function () {
                if ($.inArray($(this).attr('id'), availableDays) === -1) {
                    $(this).attr('disabled', 'disabled');
                } else {
                    $(this).removeAttr('disabled');
                }
            });

            $('.days').each(function () {
                if (days.includes($(this).attr('id'))) {
                    $(this).click();
                }
            });

            $('#startDate').datepicker({
                minDate: new Date()
            });

            console.log(event);

            if (event.length > 1) {
                let brokenStartDate = event.start_date.split('/') || null;
                $('#endDate').datepicker({
                    minDate: new Date(
                        parseInt(brokenStartDate[2]),
                        parseInt(brokenStartDate[0] - 1),
                        parseInt(brokenStartDate[1])
                    )
                });
            }

            $('#startDate').on('change', function () {
                let date = $(this).val();

                if (date == '') {
                    $('#endDate').val('');
                    $('#endDate').datepicker('disable');
                    $('#endDate').attr('disabled', 'disabled');

                    $('.days').each(function () {
                        if ($(this).val() == '1') {
                            $(this).click();
                        }
                    });
                } else {
                    let brokenDate = date.split('/');

                    $('#endDate').removeAttr('disabled');
                    $('#endDate').datepicker('enable')
                    $('#endDate').datepicker( "destroy" );
                    $('#endDate').datepicker({
                        minDate: new Date(
                            parseInt(brokenDate[2]),
                            parseInt(brokenDate[0] - 1),
                            parseInt(brokenDate[1])
                        )
                    });
                }

                availableDays = [];
                let startDate = moment($(this).val(), 'MM/DD/YYYY');
                let endDate = moment($('#endDate').val(), 'MM/DD/YYYY');

                for (var m = moment(startDate); m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
                    availableDays.push(m.format('dddd').toLowerCase());
                }

                $('.days').each(function () {
                    if ($.inArray($(this).attr('id'), availableDays) === -1) {
                        $(this).attr('disabled', 'disabled');
                    } else {
                        $(this).removeAttr('disabled');
                    }
                });
            });

            $('#endDate').on('change', function () {
                if ($(this).val() == '') {
                    $('.days').each(function () {
                        if ($(this).val() == '1') {
                            $(this).click();
                        }
                    });
                }

                availableDays = [];
                let startDate = moment($('#startDate').val(), 'MM/DD/YYYY');
                let endDate = moment($(this).val(), 'MM/DD/YYYY');

                for (var m = moment(startDate); m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
                    availableDays.push(m.format('dddd').toLowerCase());
                }

                $('.days').each(function () {
                    if ($.inArray($(this).attr('id'), availableDays) === -1) {
                        $(this).attr('disabled', 'disabled');
                    } else {
                        $(this).removeAttr('disabled');
                    }
                });
            });

            $('.days').on('change', function () {
                if ($(this).val() == 1) {
                    $(this).val('0');
                } else {
                    $(this).val('1');
                } 
            });

            $('form').on('submit', function (e) {
                e.preventDefault();
                let daysSelectedCount = 0;

                $('.days').each(function () {
                    if ($(this).val() == '1') {
                        daysSelectedCount++;
                    }
                });

                if (daysSelectedCount == 0) {
                    toastr.error('Atleast 1 day should be selected.', 'Event')
                }

                $.ajax({
                    type: 'POST',
                    url: '/event',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    encode: true,
                }).done(function (response) {
                    if (response.success) {
                        $('#calendar').fullCalendar('removeEvents');

                        response.data.events.forEach(function (item, index) {
                            $('#calendar').fullCalendar('renderEvent', {title: item.title, start: item.start, end: item.end}, true);
                        });

                        toastr.success('Successfully saved.', 'Event')
                    }
                });
            });

            $('#calendar').fullCalendar({
                header: {
                    left: 'title',
                    center: '',
                    right: 'prev,next today'
                },
                defaultDate: moment().format('YYYY-MM-DD'),
                navLinks: false, // can click day/week names to navigate views
                editable: false,
                eventLimit: false, // allow "more" link when too many events
                events: events,
                eventTextColor: 'white'
            });
        });
    </script>
@endsection