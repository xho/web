// Vue configs...

Vue.config.devtools = true;

// ERB & underscore style delimiters, avoid conflict with Twig {{ }}
Vue.options.delimiters = ['<%', '%>']

// CKeditor configs...

var ckeditorConfig = {
    configFull: {
        toolbar: [
            { name: 'document', groups: [ 'mode' ], items: [ 'Source'] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'paragraph', groups: [ 'list','blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            { name: 'editAttributes', items: [ 'Attr' ] },
            { name: 'editing', groups: [ 'find'], items: [ 'Find', 'Replace' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Formula' ] },
            { name: 'tools', items: [ 'ShowBlocks', 'AutoCorrect' ] },
            { name: 'styles', items: [ 'Format' , 'Styles'] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        ],
        allowedContent: true,
        language: BEDITA.currLang2,
        entities:false,
        fillEmptyBlocks:false,
        forcePasteAsPlainText:true,
        startupOutlineBlocks: true,
    },

    configNormal: {
        toolbar: [
            { name: 'document', groups: [ 'mode' ], items: [ 'Source'] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
            { name: 'links', items: [ 'Link', 'Unlink'] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        ],
        allowedContent: true,
        language: BEDITA.currLang2,
        entities:false,
        fillEmptyBlocks:false,
        forcePasteAsPlainText:true,
        startupOutlineBlocks: true,
    },

    configSimple: {
        toolbar: [
            { name: 'document', groups: [ 'mode' ], items: [ 'Source'] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'links', items: [ 'Link', 'Unlink'] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Undo', 'Redo' ] },
            { name: 'tools', items: [ 'ShowBlocks' ] },
        ],
        allowedContent: true,
        language: BEDITA.currLang2,
        entities:false,
        fillEmptyBlocks:false,
        forcePasteAsPlainText:true,
        startupOutlineBlocks: true,
    },
};