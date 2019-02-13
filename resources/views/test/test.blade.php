@extends('layouts.main')

@section('title', 'test fix column')

@section('js')

    <script type="text/javascript">
        $.jgrid.no_legacy_api = true;
        $.jgrid.useJSON = true;
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
    //<![CDATA[
        /*global $ */
        /*jslint devel: true, browser: true, plusplus: true, nomen: true, unparam: true */
        $(function () {
    $("body").show();
            "use strict";
            var mydata = [
                    { id: "1",  invdate: "2007-10-01", name: "test",   note: "note",   amount: "200.00", tax: "10.00", closed: true,  ship_via: "TN", total: "210.00" },
                    { id: "2",  invdate: "2007-10-02", name: "test2",  note: "note2",  amount: "300.00", tax: "20.00", closed: false, ship_via: "FE", total: "320.00" },
                    { id: "3",  invdate: "2007-09-01", name: "test3",  note: "note3",  amount: "400.00", tax: "30.00", closed: false, ship_via: "FE", total: "430.00" },
                    { id: "4",  invdate: "2007-10-04", name: "test4",  note: "note4",  amount: "200.00", tax: "10.00", closed: true,  ship_via: "TN", total: "210.00" },
                    { id: "5",  invdate: "2007-10-31", name: "test5",  note: "note5",  amount: "300.00", tax: "20.00", closed: false, ship_via: "FE", total: "320.00" },
                    { id: "6",  invdate: "2007-09-06", name: "test6",  note: "note6",  amount: "400.00", tax: "30.00", closed: false, ship_via: "FE", total: "430.00" },
                    { id: "7",  invdate: "2007-10-04", name: "test7",  note: "note7",  amount: "200.00", tax: "10.00", closed: true,  ship_via: "TN", total: "210.00" },
                    { id: "8",  invdate: "2007-10-03", name: "test8",  note: "note8",  amount: "300.00", tax: "20.00", closed: false, ship_via: "FE", total: "320.00" },
                    { id: "9",  invdate: "2007-09-01", name: "test9",  note: "note9",  amount: "400.00", tax: "30.00", closed: false, ship_via: "TN", total: "430.00" },
                    { id: "10", invdate: "2007-09-08", name: "test10", note: "note10", amount: "500.00", tax: "30.00", closed: true,  ship_via: "TN", total: "530.00" },
                    { id: "11", invdate: "2007-09-08", name: "test11", note: "note11", amount: "500.00", tax: "30.00", closed: false, ship_via: "FE", total: "530.00" },
                    { id: "12", invdate: "2007-09-10", name: "test12", note: "note12", amount: "500.00", tax: "30.00", closed: false, ship_via: "FE", total: "530.00" }
                ],
                $grid = $("#list"),
                initDateEdit = function (elem) {
                    $(elem).datepicker({
                        dateFormat: "dd-M-yy",
                        autoSize: true,
                        //showOn: "button", // it dosn't work in searching dialog
                        changeYear: true,
                        changeMonth: true,
                        showButtonPanel: true,
                        showWeek: true
                    });
                },
                initDateSearch = function (elem) {
                    setTimeout(function () {
                        $(elem).datepicker({
                            dateFormat: "dd-M-yy",
                            autoSize: true,
                            changeYear: true,
                            changeMonth: true,
                            showWeek: true,
                            showButtonPanel: true
                        });
                    }, 50);
                },
                numberTemplate = {formatter: "number", align: "right", sorttype: "number", editable: true,
                    searchoptions: { sopt: ["eq", "ne", "lt", "le", "gt", "ge", "nu", "nn", "in", "ni"] }},
                arOps = ["eq", "ne", "lt", "le", "gt", "ge", "bw", "bn", "in", "ni", "ew", "en", "cn", "nc"],
                resizeColumnHeader = function () {console.log('fuck you')
                    var rowHight, resizeSpanHeight,
                    // get the header row which contains
                    headerRow = $(this).closest("div.ui-jqgrid-view")
                        .find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");
        
                    // reset column height
                    headerRow.find("span.ui-jqgrid-resize").each(function () {
                        this.style.height = "";
                    });
        
                    // increase the height of the resizing span
                    resizeSpanHeight = "height: " + headerRow.height() + "px !important; cursor: col-resize;";
                    headerRow.find("span.ui-jqgrid-resize").each(function () {
                        this.style.cssText = resizeSpanHeight;
                    });
        
                    // set position of the dive with the column header text to the middle
                    rowHight = headerRow.height();
                    headerRow.find("div.ui-jqgrid-sortable").each(function () {
                        var ts = $(this);
                        ts.css("top", (rowHight - ts.outerHeight()) / 2 + "px");
                    });
                },
                fixPositionsOfFrozenDivs = function () {
                    var $rows;
                    if (typeof this.grid.fbDiv !== "undefined") {
        console.log('shitty fuck')
                        $rows = $(">div>table.ui-jqgrid-btable>tbody>tr", this.grid.bDiv);
                        $(">table.ui-jqgrid-btable>tbody>tr", this.grid.fbDiv).each(function (i) {
                            var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                            if ($(this).hasClass("jqgrow")) {
                                $(this).height(rowHight);
                                rowHightFrozen = $(this).height();
                                if (rowHight !== rowHightFrozen) {
                                    $(this).height(rowHight + (rowHight - rowHightFrozen));
                                }
                            }
                        });
                        $(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
                        $(this.grid.fbDiv).css($(this.grid.bDiv).position());
                    }
                    if (typeof this.grid.fhDiv !== "undefined") {
        console.log('shitty fuck')
                        $rows = $(">div>table.ui-jqgrid-htable>thead>tr", this.grid.hDiv);
                        $(">table.ui-jqgrid-htable>thead>tr", this.grid.fhDiv).each(function (i) {
                            var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                            $(this).height(rowHight);
                            rowHightFrozen = $(this).height();
                            if (rowHight !== rowHightFrozen) {
                                $(this).height(rowHight + (rowHight - rowHightFrozen));
                            }
                        });
                        $(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
                        $(this.grid.fhDiv).css($(this.grid.hDiv).position());
                    }
                },
                fixGboxHeight = function () {
                    var gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight(),
                        pagerHeight = $(this.p.pager).outerHeight();
        
                    $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
                    gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight();
                    pagerHeight = $(this.p.pager).outerHeight();
                    $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
                },
                lastSel;

            $.extend($.jgrid.search, {multipleSearch: true, multipleGroup: true});

            $grid.jqGrid({
                datatype: "local",
                data: mydata,
                colNames: ["Client", "Date", "Amount", "Tax", "Total", "Closed", "Shipped via", "Notes"],
                colModel: [
                    { name: "name", align: "center", editable: false, width: 65, editrules: {required: true}, frozen: true },
                    { name: "invdate", width: 80, align: "center", frozen: true , sorttype: "date",
                        formatter: "date", formatoptions: { newformat: "d-M-Y" }, editable: true, datefmt: "d-M-Y",
                        editoptions: { dataInit: initDateEdit },
                        searchoptions: { sopt: ["eq", "ne", "lt", "le", "gt", "ge"], dataInit: initDateSearch } },
                    { name: "amount", width: 75, template: numberTemplate },
                    { name: "tax", width: 52, template: numberTemplate },
                    { name: "total", width: 60, template: numberTemplate },
                    {name: "closed", width: 70, align: "center", editable: true, formatter: "checkbox",
                        edittype: "checkbox", editoptions: {value: "Yes:No", defaultValue: "Yes"},
                        stype: "select", searchoptions: { sopt: ["eq", "ne"], value: ":Any;true:Yes;false:No" } },
                    {name: "ship_via", width: 105, align: "center", editable: true, formatter: "select",
                        edittype: "select", editoptions: { value: "FE:FedEx;TN:TNT;IN:Intim", defaultValue: "IN" },
                        stype: "select", searchoptions: { sopt: ["eq", "ne"], value: ":Any;FE:FedEx;TN:TNT;IN:IN" } },
                    { name: "note", width: 60, sortable: false, editable: true, edittype: "textarea" }
                ],
                rowNum: 10,
                rowList: [5, 10, 20],
                pager: "#pager",
                rownumbers: true,
                //multiselect: true,
                autoencode: true,
                ignoreCase: true,
                sortname: "invdate",
                //viewrecords: true,
                sortorder: "desc",
                shrinkToFit: false,
                width: 550,
                height: "100%",
                editurl: "clientArray",
                // resizeStop: function () {
                //     resizeColumnHeader.call(this);
                //     fixPositionsOfFrozenDivs.call(this);
                //     fixGboxHeight.call(this);
                // }
            }).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
                fixPositionsOfFrozenDivs.call(this);
            });
            $grid.jqGrid("navGrid", "#pager", {cloneToTop: true});
            // $grid.jqGrid("gridResize", {
            //     minWidth: 450,
            //     stop: function () {
            //         fixPositionsOfFrozenDivs.call($grid[0]);
            //         fixGboxHeight.call($grid[0]);
            //     }
            // });

            $grid.jqGrid("filterToolbar", {stringResult: true, searchOnEnter: false, defaultSearch: "cn"});
            // $grid.jqGrid("setColProp", "cb", {frozen: true});
            $grid.jqGrid("setGridParam", {cellEdit: false, sortable: false});
            $grid.jqGrid("setFrozenColumns");
            $grid.jqGrid("setGridParam", {cellEdit: true, sortable: true});
            fixPositionsOfFrozenDivs.call($('#list')[0]);

            // try {
            //     var p = $grid.jqGrid("getGridParam"), tid = $.jgrid.jqID(p.id), colModel = p.colModel, i, n = colModel.length, cm,
            //         skipIds = [];

            //     for (i = 0; i < n; i++) {
            //         cm = colModel[i];
            //         if ($.inArray(cm.name, ["cb", "rn", "subgrid"]) >=0 || cm.frozen) {
            //             skipIds.push("#jqgh_" + tid + "_" + $.jgrid.jqID(cm.name));
            //         }
            //     }

            //     $grid.jqGrid("setGridParam", {sortable: {options: {
            //         items: skipIds.length > 0 ? ">th:not(:has(" + skipIds.join(",") + "),:hidden)" : ">th:not(:hidden)"
            //     }}});

            //     $grid.jqGrid("sortableColumns", $($grid[0].grid.hDiv).find(".ui-jqgrid-labels"));
            // } catch (e) {}
        });
    //]]>
    </script>

@endsection

@section('body')

    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <div class='col-md-12' style="padding:0 0 15px 0">
                <table id="list" class="table table-striped"></table>
                    <div id="pager"></div>
            </div>
        </div>
    </div>
@endsection