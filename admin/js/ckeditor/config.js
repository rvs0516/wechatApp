/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    config.toolbar = 'Full';
    config.toolbar_Full = [
       ['Source','-','NewPage','Preview','-','Templates'],

       ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
       ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
  
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor']
    ]; 
};
