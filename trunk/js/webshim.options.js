(function () {
  webshims.setOptions('forms', {
    lazyCustomMessages: true,
    replaceValidationUI: true,
    customDatalist: "auto",
    list: {
      "filter": "^"
    },
    iVal: {
      "sel": ".ws-validate",
      "handleBubble": "hide",
      "recheckDelay": 400,
      "fieldWrapper": ":not(span):not(label):not(em):not(strong):not(p)",
      "events": "focusout change",
      "errorClass": "user-error",
      "errorWrapperClass": "ws-invalid",
      "successWrapperClass": "ws-success",
      "errorBoxClass": "ws-errorbox",
      "errorMessageClass": "ws-errormessage",
      "fx": "slide",
      "submitCheck": false
    }
  });
  webshims.polyfill('forms');
})();