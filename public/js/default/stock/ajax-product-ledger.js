$.inlineEdit({
    expiry: appName + '/stock/product-ledger-date/type/expiry/id/'
}, {
    animate: false,
    filterElementValue: function ($o) {
        return $o.html().trim();
    },
    afterSave: function () {
    }
});