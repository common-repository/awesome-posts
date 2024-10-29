<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$css = '';

$css .= '.arifix-ap-wrapper.afx-grid-' . esc_html($id) . ' {
background-color: ' . esc_html($set->gridBgColor) . ';
padding: ' . esc_html($set->gridPadding->top) . 'px ' . esc_html($set->gridPadding->right) . 'px ' . esc_html($set->gridPadding->bottom) . 'px ' . esc_html($set->gridPadding->left) . 'px;
margin: ' . esc_html($set->gridMargin->top) . 'px ' . esc_html($set->gridMargin->right) . 'px ' . esc_html($set->gridMargin->bottom) . 'px ' . esc_html($set->gridMargin->left) . 'px;
}

.arifix-ap-wrapper.afx-grid-' . esc_html($id) . ' .arifix-ap-posts {
gap: ' . esc_html($set->gridGap) . 'px;
}

.arifix-ap-wrapper.afx-grid-' . esc_html($id) . ' .ap-date {
background-color: ' . esc_html($set->btnBgColor) . ';
color: ' . esc_html($set->btnColor) . ';
}

.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .arifix-ap-posts {
grid-template-columns: repeat(' . esc_html($set->gridColumnsD) . ', 1fr);
}

@media screen and (max-width: 991px) {
.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .arifix-ap-posts {
    grid-template-columns: repeat(' . esc_html($set->gridColumnsT) . ', 1fr);
}
}

@media screen and (max-width: 767px) {
.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .arifix-ap-posts {
    grid-template-columns: repeat(' . esc_html($set->gridColumnsM) . ', 1fr);
}
}';

if (esc_html($set->shFont)) {
    $font = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->shFont), "http")
        ? ARIFIX_AP_Helper::arifix_ap_fonts_url_to_name(esc_html($set->shFont))
        : esc_html($set->shFont);
    $font_url = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->shFont), "http")
        ? esc_html($set->shFont)
        : 'https://fonts.googleapis.com/css?family=' . str_replace(" ", '+', esc_html($set->shFont)) . '&display=swap';

    $css .= '@font-face {font-family: "' . $font . '";src: url("' . $font_url . '");}.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-grid-title{font-family: "' . $font . '"}';
}

$css .= '.arifix-ap-wrapper.afx-grid-' . esc_html($id) . ' .ap-grid-title{
font-size: ' . esc_html($set->shFontSize) . 'px;
font-weight: ' . esc_html($set->shFontWeight) . ';
font-style: ' . esc_html($set->shFontStyle) . ';
color: ' . esc_html($set->shColor) . ';
text-decoration: ' . esc_html($set->shTextDecoration) . ';
text-transform: ' . esc_html($set->shTextTransform) . ';
line-height: ' . esc_html($set->shLineHeight) . 'px;
padding: ' . esc_html($set->shPadding->top) . 'px ' . esc_html($set->shPadding->right) . 'px ' . esc_html($set->shPadding->bottom) . 'px ' . esc_html($set->shPadding->left) . 'px;
margin: ' . esc_html($set->shMargin->top) . 'px ' . esc_html($set->shMargin->right) . 'px ' . esc_html($set->shMargin->bottom) . 'px ' . esc_html($set->shMargin->left) . 'px;
letter-spacing: ' . esc_html($set->shLetterSpacing) . 'px;
word-spacing: ' . esc_html($set->shWordSpacing) . 'px;
text-align: ' . esc_html($set->shAlignment) . ';
}';

if (esc_html($set->titleFont)) {
    $font = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->titleFont), "http")
        ? ARIFIX_AP_Helper::arifix_ap_fonts_url_to_name(esc_html($set->titleFont))
        : esc_html($set->titleFont);
    $font_url = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->titleFont), "http")
        ? esc_html($set->titleFont)
        : 'https://fonts.googleapis.com/css?family=' . str_replace(" ", '+', esc_html($set->titleFont)) . '&display=swap';

    $css .= '@font-face {font-family: "' . $font . '";src: url("' . $font_url . '");}.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-title{font-family: "' . $font . '"}';
}

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-title{
font-size: ' . esc_html($set->titleFontSize) . 'px;
font-weight: ' . esc_html($set->titleFontWeight) . ';
font-style: ' . esc_html($set->titleFontStyle) . ';
color: ' . esc_html($set->titleColor) . ';
text-decoration: ' . esc_html($set->titleTextDecoration) . ';
text-transform: ' . esc_html($set->titleTextTransform) . ';
line-height: ' . esc_html($set->titleLineHeight) . 'px;
padding: ' . esc_html($set->titlePadding->top) . 'px ' . esc_html($set->titlePadding->right) . 'px ' . esc_html($set->titlePadding->bottom) . 'px ' . esc_html($set->titlePadding->left) . 'px;
margin: ' . esc_html($set->titleMargin->top) . 'px ' . esc_html($set->titleMargin->right) . 'px ' . esc_html($set->titleMargin->bottom) . 'px ' . esc_html($set->titleMargin->left) . 'px;
letter-spacing: ' . esc_html($set->titleLetterSpacing) . 'px;
word-spacing: ' . esc_html($set->titleWordSpacing) . 'px;
text-align: ' . esc_html($set->titleAlignment) . ';
}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-title:hover{color: ' . esc_html($set->titleHoverColor) . ';}';

if (esc_html($set->excerptFont)) {
    $font = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->excerptFont), "http")
        ? ARIFIX_AP_Helper::arifix_ap_fonts_url_to_name(esc_html($set->excerptFont))
        : esc_html($set->excerptFont);
    $font_url = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->excerptFont), "http")
        ? esc_html($set->excerptFont)
        : 'https://fonts.googleapis.com/css?family=' . str_replace(" ", '+', esc_html($set->excerptFont)) . '&display=swap';

    $css .= '@font-face {font-family: "' . $font . '";src: url("' . $font_url . '");}.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-excerpt{font-family: "' . $font . '"}';
}

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-excerpt{
font-size: ' . esc_html($set->excerptFontSize) . 'px;
font-weight: ' . esc_html($set->excerptFontWeight) . ';
font-style: ' . esc_html($set->excerptFontStyle) . ';
color: ' . esc_html($set->excerptColor) . ';
text-decoration: ' . esc_html($set->excerptTextDecoration) . ';
text-transform: ' . esc_html($set->excerptTextTransform) . ';
line-height: ' . esc_html($set->excerptLineHeight) . 'px;
padding: ' . esc_html($set->excerptPadding->top) . 'px ' . esc_html($set->excerptPadding->right) . 'px ' . esc_html($set->excerptPadding->bottom) . 'px ' . esc_html($set->excerptPadding->left) . 'px;
margin: ' . esc_html($set->excerptMargin->top) . 'px ' . esc_html($set->excerptMargin->right) . 'px ' . esc_html($set->excerptMargin->bottom) . 'px ' . esc_html($set->excerptMargin->left) . 'px;
letter-spacing: ' . esc_html($set->excerptLetterSpacing) . 'px;
word-spacing: ' . esc_html($set->excerptWordSpacing) . 'px;
text-align: ' . esc_html($set->excerptAlignment) . ';
}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-featured-img{height: ' . esc_html($set->postImageHeight) . 'px;}';

if (esc_html($set->metaFont)) {
    $font = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->metaFont), "http")
        ? ARIFIX_AP_Helper::arifix_ap_fonts_url_to_name(esc_html($set->metaFont))
        : esc_html($set->metaFont);
    $font_url = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->metaFont), "http")
        ? esc_html($set->metaFont)
        : 'https://fonts.googleapis.com/css?family=' . str_replace(" ", '+', esc_html($set->metaFont)) . '&display=swap';

    $css .= '@font-face {font-family: "' . $font . '";src: url("' . $font_url . '");}.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-meta{font-family: "' . $font . '"}';
}

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-meta{
font-size: ' . esc_html($set->metaFontSize) . 'px;
font-weight: ' . esc_html($set->metaFontWeight) . ';
font-style: ' . esc_html($set->metaFontStyle) . ';
color: ' . esc_html($set->metaColor) . ';
text-decoration: ' . esc_html($set->metaTextDecoration) . ';
text-transform: ' . esc_html($set->metaTextTransform) . ';
line-height: ' . esc_html($set->metaLineHeight) . 'px;
padding: ' . esc_html($set->metaPadding->top) . 'px ' . esc_html($set->metaPadding->right) . 'px ' . esc_html($set->metaPadding->bottom) . 'px ' . esc_html($set->metaPadding->left) . 'px;
margin: ' . esc_html($set->metaMargin->top) . 'px ' . esc_html($set->metaMargin->right) . 'px ' . esc_html($set->metaMargin->bottom) . 'px ' . esc_html($set->metaMargin->left) . 'px;
letter-spacing: ' . esc_html($set->metaLetterSpacing) . 'px;
word-spacing: ' . esc_html($set->metaWordSpacing) . 'px;
text-align: ' . esc_html($set->metaAlignment) . ';
}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-meta a {color: ' . esc_html($set->metaColor) . ';}
.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-meta a:hover {color: ' . esc_html($set->metaHoverColor) . ';}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-post-meta svg {color: ' . esc_html($set->metaColor) . ';}';

if (esc_html($set->btnFont)) {
    $font = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->btnFont), "http")
        ? ARIFIX_AP_Helper::arifix_ap_fonts_url_to_name(esc_html($set->btnFont))
        : esc_html($set->btnFont);
    $font_url = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->btnFont), "http")
        ? esc_html($set->btnFont)
        : 'https://fonts.googleapis.com/css?family=' . str_replace(" ", '+', esc_html($set->btnFont)) . '&display=swap';

    $css .= '@font-face {font-family: "' . $font . '";src: url("' . $font_url . '");}.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-btn{font-family: "' . $font . '"}';
}

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-btn{
font-size: ' . esc_html($set->btnFontSize) . 'px;
font-weight: ' . esc_html($set->btnFontWeight) . ';
font-style: ' . esc_html($set->btnFontStyle) . ';
color: ' . esc_html($set->btnColor) . ';
background-color: ' . esc_html($set->btnBgColor) . ';
border-radius: ' . esc_html($set->btnBorderRadius) . 'px;
text-decoration: ' . esc_html($set->btnTextDecoration) . ';
text-transform: ' . esc_html($set->btnTextTransform) . ';
line-height: ' . esc_html($set->btnLineHeight) . 'px;
padding: ' . esc_html($set->btnPadding->top) . 'px ' . esc_html($set->btnPadding->right) . 'px ' . esc_html($set->btnPadding->bottom) . 'px ' . esc_html($set->btnPadding->left) . 'px;
margin: ' . esc_html($set->btnMargin->top) . 'px ' . esc_html($set->btnMargin->right) . 'px ' . esc_html($set->btnMargin->bottom) . 'px ' . esc_html($set->btnMargin->left) . 'px;
letter-spacing: ' . esc_html($set->btnLetterSpacing) . 'px;
word-spacing: ' . esc_html($set->btnWordSpacing) . 'px;
text-align: ' . esc_html($set->btnAlignment) . ';
border-style: ' . esc_html($set->btnBorder->type) . ';
border-color: ' . esc_html($set->btnBorder->color) . ';
border-top-width: ' . esc_html($set->btnBorder->top) . 'px;
border-right-width: ' . esc_html($set->btnBorder->right) . 'px;
border-bottom-width: ' . esc_html($set->btnBorder->bottom) . 'px;
border-left-width: ' . esc_html($set->btnBorder->left) . 'px;
}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-btn:hover{
background-color: ' . esc_html($set->btnBgHoverColor) . ';
color: ' . esc_html($set->btnHoverColor) . ';
}';

if (esc_html($set->btnLmFont)) {
    $font = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->btnLmFont), "http")
        ? ARIFIX_AP_Helper::arifix_ap_fonts_url_to_name(esc_html($set->btnLmFont))
        : esc_html($set->btnLmFont);
    $font_url = ARIFIX_AP_Helper::arifix_ap_check_string_contains(esc_html($set->btnLmFont), "http")
        ? esc_html($set->btnLmFont)
        : 'https://fonts.googleapis.com/css?family=' . str_replace(" ", '+', esc_html($set->btnLmFont)) . '&display=swap';

    $css .= '@font-face {font-family: "' . $font . '";src: url("' . $font_url . '");}.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-more-btn{font-family: "' . $font . '"}';
}

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-more-btn{
font-size: ' . esc_html($set->btnLmFontSize) . 'px;
font-weight: ' . esc_html($set->btnLmFontWeight) . ';
font-style: ' . esc_html($set->btnLmFontStyle) . ';
color: ' . esc_html($set->btnLmColor) . ';
background-color: ' . esc_html($set->btnLmBgColor) . ';
border-radius: ' . esc_html($set->btnLmBorderRadius) . 'px;
text-decoration: ' . esc_html($set->btnLmTextDecoration) . ';
text-transform: ' . esc_html($set->btnLmTextTransform) . ';
line-height: ' . esc_html($set->btnLmLineHeight) . 'px;
padding: ' . esc_html($set->btnLmPadding->top) . 'px ' . esc_html($set->btnLmPadding->right) . 'px ' . esc_html($set->btnLmPadding->bottom) . 'px ' . esc_html($set->btnLmPadding->left) . 'px;
letter-spacing: ' . esc_html($set->btnLmLetterSpacing) . 'px;
word-spacing: ' . esc_html($set->btnLmWordSpacing) . 'px;
text-align: ' . esc_html($set->btnLmAlignment) . ';
border-style: ' . esc_html($set->btnLmBorder->type) . ';
border-color: ' . esc_html($set->btnLmBorder->color) . ';
border-top-width: ' . esc_html($set->btnLmBorder->top) . 'px;
border-right-width: ' . esc_html($set->btnLmBorder->right) . 'px;
border-bottom-width: ' . esc_html($set->btnLmBorder->bottom) . 'px;
border-left-width: ' . esc_html($set->btnLmBorder->left) . 'px;
}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-more-btn:hover{
background-color: ' . esc_html($set->btnLmBgHoverColor) . ';
color: ' . esc_html($set->btnLmHoverColor) . ';
}';

$css .= '.arifix-ap-wrapper.afx-grid-' .  esc_html($id) . ' .ap-loader div{background-color: ' . esc_html($set->btnLmBgColor) . ';}';

return $css;
