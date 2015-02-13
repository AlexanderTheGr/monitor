/*
 *	Code design and implementation of the current file was done by Alexander Dimeas (a.dimeas@gmail.com).
 *	Copyright (c) 2012. All rights reserved.
 */
(function($) {
    $.fn.scheduler = function(custom) {
        var defaults = {
        }
        var $dialog = {}
        var settings = $.extend({}, defaults, custom);
        var calendar = this;
        var todayBtnClicked = false;
        init();

        function init() {
            $dialog.scheduler = $("<div></div>")
                    .dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                width: 'auto',
                modal: true
            });
            $(".tabs").tabs({
                cookie: {
                    expires: 240
                },
                select: function(event, ui) {
                    //alert(event);
                    //refetchCalendar()
                    //generateChartData();
                    if (!todayBtnClicked) {
                        setTimeout(function() {
                            $(".fc-button-today").click();
                            todayBtnClicked = true;
                        }, 10)
                    }
                }
            });
            $(".datepick").datepicker({
                numberOfMonths: [3, 1],
                dateformat: "yy-mm-dd",
                onSelect: function(dateText, inst) {
                    var arr = dateText.split("/");
                    calendar.fullCalendar('changeView', 'agendaDay')
                    calendar.fullCalendar('gotoDate', arr[2], arr[0] - 1, arr[1]);
                    refetchCalendar();
                },
                onChangeMonthYear: function(year, month, inst) {
                    settings._startMonthYear = year + "-" + month + "-01";
                    refetchCalendar();
                }
            })
            redbox()
            startCalendar()
            changestatus();
        }

        function startCalendar() {
            calendar.fullCalendar({
                theme: true,
                header: {
                    left: 'prev,next,  today',
                    center: 'title,',
                    right: 'basicDay,basicWeek,month, prevYear,nextYear'
                },

                aspectRatio: 1,
                allDaySlot: true,
                ignoreTimezone: true,
                defaultView: "month",
                editable: true,
                slotMinutes: settings._slotMinutes,
                events: settings._mainurl + settings._jsonEvents,
                defaultEventMinutes: 60,
                lazyFetching: false,
                eventDrop: function(event, delta) {

                    var data = {}
                    var start = event.start;
                    var end = event.end;
                    data.start = start.format(settings._dateFormat)
                    data.end = end.format(settings._dateFormat)
                    data.sch = event.id;
                    $.post(settings._mainurl + settings._ajaxEventSave, data, function(result) {
                        if (result > 0) {

                        } else {
                            alert(result)
                        }
                        refetchCalendar();
                    })
                },
                eventResize: function(event, dayDelta, minuteDelta, revertFunc) {

                    var data = {}
                    var start = event.start;
                    var end = event.end;
                    data.start = start.format(settings._dateFormat)
                    data.end = end.format(settings._dateFormat)
                    data.sch = event.id;
                    $.post(settings._mainurl + settings._ajaxEventSave, data, function(result) {
                        if (result > 0) {

                        } else {
                            alert(result)
                        }
                        refetchCalendar();
                    })

                },
                loading: function(bool) {
                    if (bool)
                        $('#loading').show();
                    else
                        $('#loading').hide();
                },
                eventClick: function(calEvent, jsEvent, view) {

                    if (view.name == 'month') {
                        //calendar.fullCalendar( 'changeView', 'agendaDay' )
                        //calendar.fullCalendar( 'gotoDate', calEvent.start);
                        //return;
                    }


                    var start = calEvent.start.format(settings._dateFormat)
                    var end = calEvent.end.format(settings._dateFormat)
                    var title = calEvent.start.format("dd/mm/yyyy HH:MM") + " - " + calEvent.end.format("dd/mm/yyyy HH:MM");


                    var data = {
                        sch: calEvent.id,
                        start: start,
                        end: end
                    }

                    $.post(settings._mainurl + settings._ajaxEventForm, data, function(result) {
                        var params = {
                            title: title,
                            buttons: {}
                        };
                        var StrippedString = result.replace(/(<([^>]+)>)/ig, "");


                        if (settings._ajaxEventSaveAccess == 'admin') {
                            if (settings._clinicdaynotavalablemsg != StrippedString) {
                                params.buttons[settings._lang.SAVE] = function() {
                                    saveevent(data)
                                }
                            }
                        }
                        if (settings._ajaxEventDeleteAccess == 'admin') {
                            params.buttons[settings._lang.DELETE] = function() {
                                if (confirm("Are you sure")) {
                                    deleteevent(data)
                                }
                            }
                        }
                        params.buttons[settings._lang.CANCEL] = function() {
                            refetchCalendar();
                        }
                        if (result != "") {
                            $dialog.scheduler.dialog(params)
                            $dialog.scheduler.html(result);
                            $dialog.scheduler.dialog("close");
                            $dialog.scheduler.dialog("open");
                            loadUi();
                        }

                    })

                },
                dayClick: function(date, allDay, jsEvent, view) {
                    var start = date.format(settings._dateFormat)
                    var data = {}
                    if (view.name == 'month') {
                        //calendar.fullCalendar( 'changeView', 'agendaDay' )
                        //calendar.fullCalendar( 'gotoDate',date);
                        //return;
                    }
                    data.start = start;



                    $.post(settings._mainurl + settings._ajaxEventForm, data, function(result) {
                        var params = {
                            title: '',
                            buttons: {}
                        };
                        var StrippedString = result.replace(/(<([^>]+)>)/ig, "");


                        if (settings._ajaxEventSaveAccess == 'admin') {
                            if (settings._clinicdaynotavalablemsg != StrippedString) {
                                params.buttons[settings._lang.SAVE] = function() {
                                    var data = {
                                        start: start
                                    };

                                    saveevent(data)

                                }
                            }
                        }
                        params.buttons[settings._lang.CANCEL] = function() {

                            refetchCalendar();
                        }

                        if (result != "") {
                            params.title = settings._lang.INSERT_EVENT;
                            $dialog.scheduler.dialog(params)
                            $dialog.scheduler.html("");
                            $dialog.scheduler.html(result);
                            $dialog.scheduler.dialog("close");
                            $dialog.scheduler.dialog("open");
                        }
                        loadUi();
                    })
                },
                eventRender: function(event, element) {
                    element.find('.fc-event-title').html(element.find('.fc-event-title').text());
                },
                eventAfterRender: function(event, element, view) {

                    if (event.id == undefined) {
                        $(element).css('width', '2px');
                    }
                }

            });
        }
        function deleteevent(data) {
            var attributes = new Array();
            data.id = data.sch;
            $(".formfield_" + settings._dataTableId + "").each(function() {
                //alert($(this).attr("name"))
                if ($(this).attr("ref") != "") {
                    data[$(this).attr("ref")] = $(this).val();
                    //bValid = bValid && checkLength($(this), $(this).attr("ref"), 1, 16 );
                }
            })
            data["attrs"] = {}
            $(".arrtformfield_" + settings._dataTableId + "").each(function() {
                data["attrs"][$(this).attr("ref")] = ($(this).val());

            })
            ProgressBar.displayProgressBar();
            $.post(settings._mainurl + settings._ajaxEventDelete, data, function(result) {
                ProgressBar.hideProgressBar();
                refetchCalendar();
            })
        }

        function saveevent(data) {


            var attributes = new Array();
            data.id = data.sch;
            $(".formfield_" + settings._dataTableId + "").each(function() {
                //alert($(this).attr("name"))
                if ($(this).attr("ref") != "") {
                    data[$(this).attr("ref")] = $(this).val();
                    //bValid = bValid && checkLength($(this), $(this).attr("ref"), 1, 16 );
                }
            })
            data["attrs"] = {}
            $(".arrtformfield_" + settings._dataTableId + "").each(function() {
                data["attrs"][$(this).attr("ref")] = ($(this).val());

            })
            ProgressBar.displayProgressBar();
            $.post(settings._mainurl + settings._ajaxEventSave, data, function(result) {
                var attributes_id = 'scheduler';
                ProgressBar.hideProgressBar();
                if (result > 0 || result == '') {
                    var data = {
                        id: result
                    }
                    $.post(settings._mainurl + settings._jsonEvent, data, function(result) {
                        refetchCalendar();
                    })
                } else {
                    if (!errorCheck(result, settings._dataTableId)) {
                        alert(result)
                        refetchCalendar();
                    }
                }
            })
        }
        function refetchCalendar() {
            calendar.fullCalendar('refetchEvents')
            redbox();
            $dialog.scheduler.html("");
            $dialog.scheduler.dialog("close");
            $(settings._trigger).click();
        }
        function changestatus() {
            var data = {}
            $dialog.changestatus = $("<div></div>")
                    .dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                width: 'auto',
                modal: true
            });
            $(".status_btn").live("click", function() {
                data.status_id = $(this).attr("ref")
                var status = $(this).text();
                data.sch = $(this).attr("sch");
                data.patientvisit = $(this).attr("patientvisit");

                var ajaxvase
                if (data.sch > 0) {
                    ajaxvase = settings._mainurl + settings._ajaxEventSave;
                } else {
                    ajaxvase = settings._mainurl + settings._ajaxPatientSave;
                }

                var params = {
                    title: '',
                    buttons: {}
                };
                params.buttons[settings._lang.SAVE] = function() {
                    data.comments = $("#change_status_comments").val();
                    data.date = $("#statusdate").val();
                    data.time = $("#statustime").val();
                    $.post(ajaxvase, data, function(result) {
                        refetchCalendar();
                        callback.patientvisit();
                        $dialog.changestatus.dialog("close");
                    })
                }
                params.buttons[settings._lang.CANCEL] = function() {
                    $dialog.changestatus.dialog("close");
                }
                params.title = settings._lang.CHANGE_STATUS + ": ".status;
                $dialog.changestatus.dialog(params)


                //alert(TimezoneDetect());
                var date = new Date();

                $dialog.changestatus.html("<input type='hidden' class='datepicker' value='" + date.format("dd/mm/yyyy") + "' id='statusdate'>" + settings._lang.TIME + "<BR><input type='' class='timepicker' value='" + date.format("HH:MM") + "' id='statustime'><BR>" + settings._lang.COMMENTS + "<BR><textarea style='width:300px; height:100px;' id='change_status_comments'></textarea>");
                $dialog.changestatus.dialog("close");
                $dialog.changestatus.dialog("open");
                loadUi();
            })
        }
        function redbox() {
            var data = {
                d: settings._startMonthYear
            };
            $.post(settings._mainurl + settings._ajaxRedBox, data, function(result) {
                $(".redbox").remove();
                $(".ui-datepicker-calendar td").each(function() {
                    var p = this;
                    $(this).find("a").each(function() {
                        $(this).css("background", "#f2f2f2")
                        $(this).css("border", "0px");
                        $(this).css("color", "#2c8b7e");
                        var val = $.parseJSON(result);
                        if (val[$(p).attr("data-year") + "-" + (($(p).attr("data-month") * 1) + 1) + "-" + $(this).text()]) {
                            if (val[$(p).attr("data-year") + "-" + (($(p).attr("data-month") * 1) + 1) + "-" + $(this).text()]["scheduler"]) {
                                var appointments = val[$(p).attr("data-year") + "-" + (($(p).attr("data-month") * 1) + 1) + "-" + $(this).text()]["scheduler"]
                                $(p).append('<img width=8 title="' + appointments + ' ' + settings._lang.APOINTMETNS + '" class="redbox" style="position:absolute; margin-left:1px; margin-top:-18px;" src="' + settings._baseurl + '/images/red-box.png" />');
                                $(this).attr("title", appointments + ' ' + settings._lang.APOINTMETNS);
                            }
                            if (val[$(p).attr("data-year") + "-" + (($(p).attr("data-month") * 1) + 1) + "-" + $(this).text()]["clinicday"])
                                $(this).css("background", "#c8ddda")
                        }
                    })
                })
            })
        }

    }

})(jQuery);

function TimezoneDetect() {
    var dtDate = new Date('1/1/' + (new Date()).getUTCFullYear());
    var intOffset = 10000; //set initial offset high so it is adjusted on the first attempt
    var intMonth;
    var intHoursUtc;
    var intHours;
    var intDaysMultiplyBy;

    //go through each month to find the lowest offset to account for DST
    for (intMonth = 0; intMonth < 12; intMonth++) {
        //go to the next month
        dtDate.setUTCMonth(dtDate.getUTCMonth() + 1);

        //To ignore daylight saving time look for the lowest offset.
        //Since, during DST, the clock moves forward, it'll be a bigger number.
        if (intOffset > (dtDate.getTimezoneOffset() * (-1))) {
            intOffset = (dtDate.getTimezoneOffset() * (-1));
        }
    }

    return intOffset;
}