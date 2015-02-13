<?php require Yii::app()->params['widget'] . "ajaxform.php"; ?>

<script>


    var $elem = $("#order_customer_name").autocomplete({
        source: "<?php echo Yii::app()->request->baseUrl ?>/customers/customer/search",
        minLength: 2,
        select: function (event, ui) {
            $("#order_customer").val(ui.item.id);
            $("#order_customer_name").val(ui.item.label);
            $(".saveorder").click();
        }

    }),
    elemAutocomplete = $elem.data("ui-autocomplete") || $elem.data("autocomplete");
    if (elemAutocomplete) {
        elemAutocomplete._renderItem = function (ul, item) {
            var newText = String(item.value).replace(
                    new RegExp(this.term, "gi"),
                    "<span style='font-weight:bold' class='ui-state-highlight'>$&</span>");

            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a>" + newText + "</a>")
                    .appendTo(ul);
        };
    }

</script>