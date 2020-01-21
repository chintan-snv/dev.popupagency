<?php

namespace MyListing\Ext\Typography;

if ( ! defined('ABSPATH') ) {
	exit;
}

return [


    'header' => [
        'label' => 'Header',
        'settings' => [
            'divide_header_search' => [
                'type' => 'divider',
                'label' => 'Quick search placeholder',
            ],

            'header_search_size' => [
                'label' => 'Font size',
                'selector' => '.header-search > input, .search-shortcode.header-search input[type=search]',
                'option' => 'options_header-search-placeholder-options_font-size',
                'type' => 'font-size',
            ],

            'header_search_weight' => [
                'label' => 'Font weight',
                'selector' => '.header-search > input, .header-light-skin:not(.header-scroll) .header-search input, .header.header-scroll.header-scroll-light-skin .header-search input, .search-shortcode.header-search input[type=search]',
                'option' => 'options_header-search-placeholder-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_quick_results' => [
                'type' => 'divider',
                'label' => 'Quick search results',
            ],

            'quick_search_font_size' => [
                'label' => 'Font size',
                'selector' => '.instant-results ul li a span.category-name, .instant-results ul li a span.category-name',
                // 'option' => 'options_header-search-placeholder-options_font-size',
                'type' => 'font-size',
            ],

            'quick_search_font_weight' => [
                'label' => 'Font weight',
                'selector' => '.instant-results ul li a span.category-name, .instant-results ul li a span.category-name',
                // 'option' => 'options_header-search-placeholder-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_quick_view_results' => [
                'type' => 'divider',
                'label' => 'Quick search view results button',
            ],

            'quick_search_view_results_size' => [
                'label' => 'Font size',
                'selector' => '.instant-results .view-all-results',
                // 'option' => 'options_header-search-placeholder-options_font-size',
                'type' => 'font-size',
            ],

            'quick_search_view_results_weight' => [
                'label' => 'Font weight',
                'selector' => '.instant-results .view-all-results',
                // 'option' => 'options_header-search-placeholder-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_header_menu' => [
                'type' => 'divider',
                'label' => 'Main menu',
            ],

            'header_menu_size' => [
                'label' => 'Font size',
                'selector' => '.i-nav > ul > li',
                'option' => 'options_menu-item-options_font-size',
                'type' => 'font-size',
            ],

            'header_menu_weight' => [
                'label' => 'Font weight',
                'selector' => '.i-nav > ul > li',
                'option' => 'options_menu-item-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_header_dropdown_menu' => [
                'type' => 'divider',
                'label' => 'Dropdown menu',
            ],

            'header_dropdown_menu_size' => [
                'label' => 'Font size',
                'selector' => '.sub-menu.i-dropdown li a, .sub-menu.i-dropdown li a, .mobile-user-menu > ul > li > a,
                .i-dropdown li a',
                // 'option' => 'options_menu-item-options_font-size',
                'type' => 'font-size',
            ],

            'header_dropdown_menu_weight' => [
                'label' => 'Font weight',
                'selector' => '.sub-menu.i-dropdown li a, .sub-menu.i-dropdown li a, .mobile-user-menu > ul > li > a,
                .i-dropdown li a',
                // 'option' => 'options_menu-item-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_user_area' => [
                'type' => 'divider',
                'label' => 'User area',
            ],

            'header_auth_size' => [
                'label' => 'Font size',
                'selector' => '.user-area .user-profile-name, .header .user-area > a, .user-profile-dropdown .user-profile-name',
                'option' => 'options_signin-register-options_font-size',
                'type' => 'font-size',
            ],

            'header_auth_weight' => [
                'label' => 'Font weight',
                'selector' => '.user-area .user-profile-name, .header .user-area > a, .user-profile-dropdown .user-profile-name',
                'option' => 'options_signin-register-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_cta_header_button' => [
                'type' => 'divider',
                'label' => 'Call to action button',
            ],

            'header_cta_size' => [
                'label' => 'Font size',
                'selector' => '.header-right .header-button a.buttons, .header-bottom-wrapper .header-button a',
                'option' => 'options_header-action-button-options_font-size',
                'type' => 'font-size',
            ],

            'header_cta_weight' => [
                'label' => 'Font weight',
                'selector' => '.header-right .header-button a.buttons, .header-bottom-wrapper .header-button a',
                'option' => 'options_header-action-button-options_font-weight',
                'type' => 'font-weight',
            ],
        ],
    ],

    'single-listing' => [
        'label' => 'Single listing',
        'settings' => [


            'divide_single_listing_editor_paragraph' => [
                'type' => 'divider',
                'label' => 'Paragraphs',
            ],

            'single_listing_editor_paragraph_size' => [
                'label' => 'Font size',
                'selector' => '.wp-editor-content p, .wp-editor-content ul li,
                .wp-editor-content ol li, .plain-text-content .pf-body p',
                // 'option' => 'options_listing-name-options_font-size',
                'type' => 'font-size',
            ],

            'single_listing_editor_paragraph_weight' => [
                'label' => 'Font weight',
                'selector' => '.wp-editor-content p, .wp-editor-content ul li,
                .wp-editor-content ol li, .plain-text-content .pf-body p',
                // 'option' => 'options_listing-name-options_font-size',
                'type' => 'font-weight',
            ],

            'single_listing_editor_paragraph_lineheight' => [
                'label' => 'Line height',
                'selector' => '.wp-editor-content p, .wp-editor-content ul li,
                .wp-editor-content ol li, .plain-text-content .pf-body p',
                // 'option' => 'options_listing-name-options_font-size',
                'type' => 'line-height',
            ],

            'single_listing_editor_paragraph_color' => [
                'label' => 'Color',
                'selector' => '.wp-editor-content p, .wp-editor-content ul li,
                .wp-editor-content ol li, .plain-text-content .pf-body p, .wp-editor-content a,
                .wp-editor-content h1, .wp-editor-content h2, .wp-editor-content h3, .wp-editor-content h4,
                .wp-editor-content h5, .wp-editor-content h6',
                // 'option' => 'options_listing-name-options_font-size',
                'type' => 'color',
            ],

            'divide_single_listing_title' => [
                'type' => 'divider',
                'label' => 'Listing title',
            ],

            'listing_title_size' => [
                'label' => 'Font size',
                'selector' => '.profile-name h1',
                'option' => 'options_listing-name-options_font-size',
                'type' => 'font-size',
            ],

            'listing_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.profile-name h1',
                'option' => 'options_listing-name-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_title_color' => [
                'label' => 'Color',
                'selector' => '.profile-name h1',
                // 'option' => 'options_listing-name-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_tagline' => [
                'type' => 'divider',
                'label' => 'Tagline',
            ],


            'listing_tagline_size' => [
                'label' => 'Font size',
                'selector' => '.profile-name h2',
                'option' => 'options_listing-tagline-options_font-size',
                'type' => 'font-size',
            ],

            'listing_tagline_weight' => [
                'label' => 'Font weight',
                'selector' => '.profile-name h2',
                'option' => 'options_listing-tagline-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_tagline_color' => [
                'label' => 'Color',
                'selector' => '.profile-name h2',
                // 'option' => 'options_listing-name-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_cta' => [
                'type' => 'divider',
                'label' => 'Call to action button',
            ],


            'listing_cta_size' => [
                'label' => 'Font size',
                'selector' => '.lmb-calltoaction > a',
                'option' => 'options_call-to-action-cover-button-options_font-size',
                'type' => 'font-size',
            ],

            'listing_cta_weight' => [
                'label' => 'Font weight',
                'selector' => '.lmb-calltoaction > a',
                'option' => 'options_call-to-action-cover-button-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_single_listing_cover_detail' => [
                'type' => 'divider',
                'label' => 'Cover detail',
            ],

            'listing_cover_detail_size' => [
                'label' => 'Font size',
                'selector' => '.price-or-date .value',
                // 'option' => 'options_call-to-action-cover-button-options_font-size',
                'type' => 'font-size',
            ],

            'listing_cover_detail_weight' => [
                'label' => 'Font weight',
                'selector' => '.price-or-date .value',
                // 'option' => 'options_call-to-action-cover-button-options_font-size',
                'type' => 'font-weight',
            ],

            'listing_cover_detail_color' => [
                'label' => 'Color',
                'selector' => '.price-or-date .value',
                // 'option' => 'options_listing-name-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_menu' => [
                'type' => 'divider',
                'label' => 'Listing menu',
            ],

            'listing_menu_size' => [
                'label' => 'Font size',
                'selector' => '.profile-header .profile-menu ul li a, .profile-header .profile-menu ul li a.listing-tab-toggle',
                'option' => 'options_listing-menu-options_font-size',
                'type' => 'font-size',
            ],

            'listing_menu_weight' => [
                'label' => 'Font weight',
                'selector' => '.profile-header .profile-menu ul li a, .profile-header .profile-menu ul li a.listing-tab-toggle',
                'option' => 'options_listing-menu-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_menu_color' => [
                'label' => 'Color',
                'selector' => '.profile-header .profile-menu ul li a, .profile-header .profile-menu ul li a.listing-tab-toggle',
                // 'option' => 'options_listing-name-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_quick_action' => [
                'type' => 'divider',
                'label' => 'Quick action title',
            ],


            'listing_qa_size' => [
                'label' => 'Font size',
                'selector' => '.quick-listing-actions > ul >li >a',
                'option' => 'options_quick-action-options_font-size',
                'type' => 'font-size',
            ],

            'listing_qa_weight' => [
                'label' => 'Font weight',
                'selector' => '.quick-listing-actions > ul >li >a',
                'option' => 'options_quick-action-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_qa_text_color' => [
                'label' => 'Text color',
                'selector' => '.quick-listing-actions > ul >li >a span',
                // 'option' => 'options_listing-name-options_font-weight',
                'type' => 'color',
            ],

            'listing_qa_icon_color' => [
                'label' => 'Icon color',
                'selector' => '.quick-listing-actions > ul >li >a > i',
                // 'option' => 'options_listing-name-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_block_title' => [
                'type' => 'divider',
                'label' => 'Block title',
            ],

            'listing_block_title_size' => [
                'label' => 'Font size',
                'selector' => '.listing-tabs .title-style-1 h5',
                'option' => 'options_block-title-options_font-size',
                'type' => 'font-size',
            ],

            'listing_block_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.listing-tabs .title-style-1 h5',
                'option' => 'options_block-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_block_icon_color' => [
                'label' => 'Icon color',
                'selector' => 'body.single-listing .title-style-1 i',
                'option' => 'options_single_listing_content_block_icon_color',
                'type' => 'color',
            ],

            'listing_block_title_color' => [
                'label' => 'Icon color',
                'selector' => '.listing-tabs .title-style-1 h5',
                // 'option' => 'options_single_listing_content_block_icon_color',
                'type' => 'color',
            ],

            'divide_single_listing_term_title' => [
                'type' => 'divider',
                'label' => 'Term title (Category, tag, term block)',
            ],

            'listing_term_size' => [
                'label' => 'Font size',
                'selector' => '#c27-single-listing .element .pf-body .listing-details li span.category-name, .block-type-tags .element .social-nav li span, .block-type-terms .element .social-nav li span',
                'option' => 'options_category-term-block-tag-options_font-size',
                'type' => 'font-size',
            ],

            'listing_term_weight' => [
                'label' => 'Font weight',
                'selector' => '#c27-single-listing .element .pf-body .listing-details li span.category-name, .block-type-tags .element .social-nav li span, .block-type-terms .element .social-nav li span',
                'option' => 'options_category-term-block-tag-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_term_color' => [
                'label' => 'Color',
                'selector' => '#c27-single-listing .element .pf-body .listing-details li span.category-name, .block-type-tags .element .social-nav li span, .block-type-terms .element .social-nav li span',
                // 'option' => 'options_category-term-block-tag-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_social_title' => [
                'type' => 'divider',
                'label' => 'Social networks title',
            ],

            'listing_socnet_size' => [
                'label' => 'Font size',
                'selector' => '.block-type-social_networks .element .social-nav li span',
                'option' => 'options_social-network-block-options_font-size',
                'type' => 'font-size',
            ],

            'listing_socnet_weight' => [
                'label' => 'Font weight',
                'selector' => '.block-type-social_networks .element .social-nav li span',
                'option' => 'options_social-network-block-options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_socnet_color' => [
                'label' => 'Color',
                'selector' => '.block-type-social_networks .element .social-nav li span',
                // 'option' => 'options_social-network-block-options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_table_font' => [
                'type' => 'divider',
                'label' => 'Table text',
            ],

            'listing_table_label_size' => [
                'label' => 'Font size',
                'selector' => '.table-block .extra-details .item-attr, .extra-details .item-property p',
                'option' => 'options_table_label_options_font-size',
                'type' => 'font-size',
            ],

            'listing_table_label_weight' => [
                'label' => 'Font weight',
                'selector' => '.table-block .extra-details .item-attr, .extra-details .item-property p',
                'option' => 'options_table_label_options_font-weight',
                'type' => 'font-weight',
            ],

            'listing_table_label_color' => [
                'label' => 'Color',
                'selector' => '.table-block .extra-details .item-attr, .extra-details .item-property p',
                // 'option' => 'options_table_label_options_font-weight',
                'type' => 'color',
            ],

            'divide_single_listing_author_related' => [
                'type' => 'divider',
                'label' => 'Related listing / Author / File title',
            ],

            'listing_single_related_author_block_size' => [
                'label' => 'Font size',
                'selector' => '.related-listing-block .event-host .host-name, .files-block .file-name',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-size',
            ],

            'listing_single_related_author_block_weight' => [
                'label' => 'Font weight',
                'selector' => '.related-listing-block .event-host .host-name, .files-block .file-name',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-weight',
            ],

            'listing_single_related_author_block_color' => [
                'label' => 'Color',
                'selector' => '.related-listing-block .event-host .host-name, .files-block .file-name',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'color',
            ],

            'divide_single_listing_tab_accordion' => [
                'type' => 'divider',
                'label' => 'Tab / Accordion title',
            ],

            'listing_single_tab_acc_size' => [
                'label' => 'Font size',
                'selector' => '.block-type-accordion .panel-title a, .block-type-tabs .bl-tabs .nav-tabs>li>a',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-size',
            ],

            'listing_single_tab_acc_weight' => [
                'label' => 'Font weight',
                'selector' => '.block-type-accordion .panel-title a, .block-type-tabs .bl-tabs .nav-tabs>li>a',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-weight',
            ],

            'listing_single_tab_acc_color' => [
                'label' => 'Font color',
                'selector' => '.block-type-accordion .panel-title a, .block-type-tabs .bl-tabs .nav-tabs>li>a',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'color',
            ],



        ],
    ],

    'search_forms' => [
        'label' => 'Search Forms',
        'settings' => [

            'divide_search_form_labels' => [
                'type' => 'divider',
                'label' => 'Basic and advanced form filter labels',
            ],

            'search_label_size' => [
                'label' => 'Font size (Recommmended values: 12 to 16)',
                'selector' => '.finder-search .form-group input, .featured-search .form-group label, .finder-search .form-group label, .cts-term-hierarchy.form-group.md-group .go-back-btn,.md-group input:focus ~ label,
                .featured-search .radius .amount, .radius .amount, .featured-search input, .featured-search
                .form-group .select2-container--default .select2-selection--single .select2-selection__rendered,
                .finder-search .form-group .select2-container--default .select2-selection--single .select2-selection__rendered, .featured-search .radius.proximity-slider .amount, .finder-search .select2-container .select2-search--inline .select2-search__field, .finder-search .select2-container--default .select2-selection--multiple .select2-selection__choice, .pac-container .pac-item, .tags-nav li .md-checkbox label, #finderSearch .datepicker-wrapper input',
                // 'option' => 'options_basic-and-advanced-search-form-label-field-options_font-size',
                'type' => 'font-size',
            ],

            'search_label_weight' => [
                'label' => 'Font weight',
                'selector' => '.finder-search .form-group input, .featured-search .form-group label, .finder-search .form-group label,
                .cts-term-hierarchy.form-group.md-group .go-back-btn, .md-group input:focus ~ label, .featured-search, .featured-search .radius.proximity-slider .amount, .radius .amount,
                .featured-search input, .featured-search
                .form-group .select2-container--default .select2-selection--single .select2-selection__rendered,
                .finder-search .form-group .select2-container--default .select2-selection--single .select2-selection__rendered, .finder-search .select2-container .select2-search--inline .select2-search__field, .finder-search .select2-container--default .select2-selection--multiple .select2-selection__choice,.pac-container .pac-item,
                .tags-nav li .md-checkbox label, #finderSearch .datepicker-wrapper input',
                // 'option' => 'options_basic-and-advanced-search-form-label-field-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_basic_tab' => [
                'type' => 'divider',
                'label' => 'Basic form tabs',
            ],

            'search_basic_tab_size' => [
                'label' => 'Font size',
                'selector' => '.fs-tabs .nav-tabs>li>a, .transparent .fs-tabs .nav-tabs>li>a',
                'option' => 'options_search-tab-options_font-size',
                'type' => 'font-size',
            ],

            'search_basic_tab_weight' => [
                'label' => 'Font weight',
                'selector' => '.fs-tabs .nav-tabs>li>a',
                'option' => 'options_search-tab-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_basic_search_button' => [
                'type' => 'divider',
                'label' => 'Basic form search button',
            ],

            'search_basic_btn_size' => [
                'label' => 'Font size',
                'selector' => '.featured-search .search',
                'option' => 'options_search-button-options_font-size',
                'type' => 'font-size',
            ],

            'search_basic_btn_weight' => [
                'label' => 'Font weight',
                'selector' => '.featured-search .search',
                'option' => 'options_search-button-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_select2_dropdown_fonts' => [
                'type' => 'divider',
                'label' => 'Dropdown filter (Affects all select2 dropdowns throughout the site)',
            ],

            'select2_dropdown_font_size' => [
                'label' => 'Font size',
                'selector' => '.select2-results__option',
                // 'option' => 'options_search-tab-options_font-size',
                'type' => 'font-size',
            ],

            'select2_dropdown_font_weight' => [
                'label' => 'Font weight',
                'selector' => '.select2-results__option',
                // 'option' => 'options_search-tab-options_font-size',
                'type' => 'font-weight',
            ],

        ],
    ],

    'preview_card' => [
        'label' => 'Preview Card',
        'settings' => [

            'divide_default_template_title' => [
                'type' => 'divider',
                'label' => 'Default template title',
            ],

            'prevcard_title_size' => [
                'label' => 'Font size',
                'selector' => '.lf-item-default .lf-item-info > h4',
                'option' => 'options_default-template-listing-title-options_font-size',
                'type' => 'font-size',
            ],

            'prevcard_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.lf-item-default .lf-item-info > h4',
                'option' => 'options_default-template-listing-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'prevcard_title_color' => [
                'label' => 'Color',
                'selector' => '.lf-item-default .lf-item-info > h4',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'color',
            ],

            'divide_alternate_template_title' => [
                'type' => 'divider',
                'label' => 'Alternate template title',
            ],

            'prevcard_alt_title_size' => [
                'label' => 'Font size',
                'selector' => '.lf-item-alternate .lf-item-info-2 h4',
                'option' => 'options_alternate_template_listing_title_options_font-size',
                'type' => 'font-size',
            ],

            'prevcard_alt_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.lf-item-alternate .lf-item-info-2 h4',
                'option' => 'options_alternate_template_listing_title_options_font-weight',
                'type' => 'font-weight',
            ],

            'prevcard_alt_title_color' => [
                'label' => 'Color',
                'selector' => '.lf-item-alternate .lf-item-info-2 h4',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'color',
            ],

            'divide_alternate_tagline' => [
                'type' => 'divider',
                'label' => 'Tagline (Alternate template)',
            ],

            'tagline_alternate_font_size' => [
                'label' => 'Font size',
                'selector' => '.lf-item-info-2 h6',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'tagline_alternate_font_weight' => [
                'label' => 'Font weight',
                'selector' => '.lf-item-info-2 h6',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-weight',
            ],

            'tagline_alternate_font_color' => [
                'label' => 'Font color',
                'selector' => '.lf-item-info-2 h6',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'color',
            ],


            'divide_list_template_title' => [
                'type' => 'divider',
                'label' => 'List template title',
            ],

            'prevcard_list_title_size' => [
                'label' => 'Font size',
                'selector' => '.lf-item.lf-item-list-view .lf-item-info > h4',
                'option' => 'options_list-template-listing-title-options_font-size',
                'type' => 'font-size',
            ],

            'prevcard_list_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.lf-item.lf-item-list-view .lf-item-info > h4',
                'option' => 'options_list-template-listing-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'prevcard_list_title_color' => [
                'label' => 'Color',
                'selector' => '.lf-item.lf-item-list-view .lf-item-info > h4',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'color',
            ],

            'divide_quick_view_title' => [
                'type' => 'divider',
                'label' => 'Quick view title',
            ],

            'quickview_title_size' => [
                'label' => 'Font size',
                'selector' => '.quick-view-modal .lf-item-info h4',
                'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'quickview_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.quick-view-modal .lf-item-info h4',
                'option' => 'options_quick-view-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_fields_below_title' => [
                'type' => 'divider',
                'label' => 'Fields below the title',
            ],

            'fields_below_title_font_size' => [
                'label' => 'Font size',
                'selector' => '.lf-item-info > ul li, .lf-item-info-2 > ul.lf-contact li',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'fields_below_title_font_weight' => [
                'label' => 'Font weight',
                'selector' => '.lf-item-info > ul li, .lf-item-info-2 > ul.lf-contact li',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_preview_term_title' => [
                'type' => 'divider',
                'label' => 'Term title (Category, Region, Tags etc.)',
            ],

            'preview_card_term_title_fontsize' => [
                'label' => 'Font size',
                'selector' => '.c27-footer-section.listing-details .category-name,
                .listing-quick-view-container .element .listing-details li .category-name',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'preview_card_term_title_fontweight' => [
                'label' => 'Font weight',
                'selector' => '.c27-footer-section.listing-details .category-name,
                .listing-quick-view-container .element .listing-details li .category-name',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_preview_head_buttons' => [
                'type' => 'divider',
                'label' => 'Head buttons',
            ],

            'preview_card_head_buttons_fontsize' => [
                'label' => 'Font size',
                'selector' => '.lf-head-btn, .lf-head-btn.open-status span, .lf-head .event-date span.e-month,
                .lf-head .event-date span.e-day, .listing-preview .lf-head-btn.formatted .rent-price span.value,
                .listing-preview .lf-head-btn.formatted .rent-price sup.out-of, .rating-preview-card i',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'preview_card_head_buttons_fontweight' => [
                'label' => 'Font weight',
                'selector' => '.lf-head-btn, .lf-head-btn.open-status span, .lf-head .event-date span.e-month,
                .lf-head .event-date span.e-day, .listing-preview .lf-head-btn.formatted .rent-price span.value,
                .listing-preview .lf-head-btn.formatted .rent-price sup.out-of, .rating-preview-card i',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_preview_card_table_details' => [
                'type' => 'divider',
                'label' => 'Details table',
            ],

            'preview_card_details_table_fontsize' => [
                'label' => 'Font size',
                'selector' => '.listing-details-3 .details-list li span',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'preview_card_details_table_fontweight' => [
                'label' => 'Font weight',
                'selector' => '.listing-details-3 .details-list li span',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_preview_card_related_listing' => [
                'type' => 'divider',
                'label' => 'Author/related listing title',
            ],

            'preview_card_author_related_listings_fontsize' => [
                'label' => 'Font size',
                'selector' => '.lf-item-container .event-host .host-name',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-size',
            ],

            'preview_card_author_related_listings_fontweight' => [
                'label' => 'Font weight',
                'selector' => '.lf-item-container .event-host .host-name',
                // 'option' => 'options_quick-view-title-options_font-size',
                'type' => 'font-weight',
            ],

        ],
    ],

    'explore' => [
        'label' => 'Explore page',
        'settings' => [

            'divide_explore_page_title' => [
                'type' => 'divider',
                'label' => 'Explore page title',
            ],

            'explore_title_size' => [
                'label' => 'Font size',
                'selector' => '.explore-head .explore-types .finder-title h2',
                'option' => 'options_explore-listing-type-menu-options_font-size',
                'type' => 'font-size',
            ],

            'explore_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.explore-head .explore-types .finder-title h2',
                'option' => 'options_explore-listing-type-menu-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_explore_page_types_title' => [
                'type' => 'divider',
                'label' => 'Listing type titles (navbar)',
            ],

            'explore_types_size' => [
                'label' => 'Font size',
                'selector' => '.explore-head .explore-types .type-info > h4',
                'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],

            'explore_types_weight' => [
                'label' => 'Font weight',
                'selector' => '.explore-head .explore-types .type-info > h4',
                'option' => 'options_explore-listing-type-menu-heading-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_explore_page_tabs_title' => [
                'type' => 'divider',
                'label' => 'Tab title (left sidebar)',
            ],

            'explore_tabs_size' => [
                'label' => 'Font size',
                'selector' => '.finder-tabs .nav>li>a p',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],
            'explore_tabs_weight' => [
                'label' => 'Font weight',
                'selector' => '.finder-tabs .nav>li>a p',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_explore_page_search_btn' => [
                'type' => 'divider',
                'label' => 'Search button',
            ],

            'explore_search_btn_size' => [
                'label' => 'Font size',
                'selector' => '.finder-search .tab-content .form-group .button-2',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],
            'explore_search_btn_weight' => [
                'label' => 'Font weight',
                'selector' => '.finder-search .tab-content .form-group .button-2',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_explore_page_reset_btn' => [
                'type' => 'divider',
                'label' => 'Återställ Filter',
            ],

            'explore_reset_btn_size' => [
                'label' => 'Font size',
                'selector' => '.reset-results-27',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],

            'explore_reset_btn_weight' => [
                'label' => 'Font weight',
                'selector' => '.reset-results-27',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_explore_page_top_details' => [
                'type' => 'divider',
                'label' => 'Details at the top of the results (listing order, number of results etc.)',
            ],

            'explore_top_details_size' => [
                'label' => 'Font size',
                'selector' => '.showing-filter a, .fl-results-no span, .fl-head a, .fl-head p',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],
            'explore_top_details_weight' => [
                'label' => 'Font weight',
                'selector' => '.showing-filter a, .fl-results-no span, .fl-head a, .fl-head p',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_explore_page_term_title' => [
                'type' => 'divider',
                'label' => 'Term tab: Term title',
            ],

            'explore_term_tab_title_size' => [
                'label' => 'Font size',
                'selector' => '.finder-search .lc-info h4, .active-taxonomy-container h1.category-name',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],

            'explore_term_tab_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.finder-search .lc-info h4, .active-taxonomy-container h1.category-name',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_explore_page_term_description' => [
                'type' => 'divider',
                'label' => 'Term tab: Term description',
            ],

            'explore_term_tab_description_size' => [
                'label' => 'Font size',
                'selector' => '.active-taxonomy-container .category-description',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-size',
            ],

            'explore_term_tab_description_weight' => [
                'label' => 'Font weight',
                'selector' => '.active-taxonomy-container .category-description',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'explore_term_tab_description_line_height' => [
                'label' => 'Line height',
                'selector' => '.active-taxonomy-container .category-description',
                // 'option' => 'options_explore-listing-type-menu-heading-options_font-size',
                'type' => 'line-height',
            ],

        ],
    ],



    'add-listing' => [
        'label' => 'Add Listing Page',
        'settings' => [

            'divide_addlisting_card_title' => [
                'type' => 'divider',
                'label' => 'Choose listing type card title',
            ],

            'add_listing_card_title_size' => [
                'label' => 'Font size',
                'selector' => '.elementor-widget-case27-add-listing-widget .ac-front-side .category-name',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-size',
            ],

            'add_listing_card_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.elementor-widget-case27-add-listing-widget .ac-front-side .category-name',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-weight',
            ],

            'divide_addlisting_step_title' => [
                'type' => 'divider',
                'label' => 'Inner step title',
            ],

            'add_listing_step_title_size' => [
                'label' => 'Font size',
                'selector' => '.add-listing-step .section-title h2',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-size',
            ],

            'add_listing_step_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.add-listing-step .section-title h2',
                // 'option' => 'options_table_label_options_font-size',
                'type' => 'font-weight',
            ],

            'divide_addlisting_nav_size' => [
                'type' => 'divider',
                'label' => 'Add listing form navigation',
            ],

            'addlist_nav_size' => [
                'label' => 'Font size',
                'selector' => '.add-listing-nav a',
                'option' => 'options_add-navigation-left-side_font-size',
                'type' => 'font-size',
            ],

            'addlist_nav_weight' => [
                'label' => 'Font weight',
                'selector' => '.add-listing-nav a',
                'option' => 'options_add-navigation-left-side_font-weight',
                'type' => 'font-weight',
            ],

            'divide_addlisting_labels' => [
                'type' => 'divider',
                'label' => 'Form labels',
            ],

            'addlist_label_size' => [
                'label' => 'Font size',
                'selector' => '#submit-job-form .field-head label',
                'option' => 'options_field-label-options_font-size',
                'type' => 'font-size',
            ],

            'addlist_label_weight' => [
                'label' => 'Font weight',
                'selector' => '#submit-job-form .field-head label',
                'option' => 'options_field-label-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_addlisting_heading' => [
                'type' => 'divider',
                'label' => 'Form heading',
            ],

            'addlist_heading_size' => [
                'label' => 'Font size',
                'selector' => '.c27-submit-listing-form .form-section .title-style-1 h5',
                'option' => 'options_form-heading-options_font-size',
                'type' => 'font-size',
            ],

            'addlist_heading_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-submit-listing-form .form-section .title-style-1 h5',
                'option' => 'options_form-heading-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_addlisting_placeholder' => [
                'type' => 'divider',
                'label' => 'Input placeholder',
            ],

            'addlist_placeholder_size' => [
                'label' => 'Font size',
                'selector' => '.c27-submit-listing-form input, .c27-submit-listing-form textarea,
                .c27-submit-listing-form .select2-container .select2-search--inline .select2-search__field,
                .c27-submit-listing-form .select2-container--default .select2-selection--single .select2-selection__rendered, #submit-job-form .small,
                .c27-submit-listing-form .select2-container--default .select2-selection--multiple .select2-selection__choice, #submit-job-form ul.c27-term-checklist label',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-size',
            ],

            'addlist_placeholder_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-submit-listing-form input, .c27-submit-listing-form textarea,
                .c27-submit-listing-form .select2-container .select2-search--inline .select2-search__field,
                .c27-submit-listing-form .select2-container--default .select2-selection--single .select2-selection__rendered, #submit-job-form .small,
                .c27-submit-listing-form .select2-container--default .select2-selection--multiple .select2-selection__choice, #submit-job-form ul.c27-term-checklist label',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_addlisting_button_preview' => [
                'type' => 'divider',
                'label' => 'Preview button',
            ],

            'addlist_preview_btn_size' => [
                'label' => 'Font size',
                'selector' => 'form .listing-form-submit-btn button[type=submit]',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-size',
            ],

            'addlist_preview_btn_weight' => [
                'label' => 'Font weight',
                'selector' => 'form .listing-form-submit-btn button[type=submit]',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-weight',
            ],
        ],
    ],

    'reviews-comments' => [
        'label' => 'Comments and reviews',
        'settings' => [
            'divide_comments_username' => [
                'type' => 'divider',
                'label' => 'Username',
            ],

            'comments_username_size' => [
                'label' => 'Font size',
                'selector' => '.comment-head h5 a',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-size',
            ],

            'comments_username_weight' => [
                'label' => 'Font weight',
                'selector' => '.comment-head h5 a',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'divide_comments_body_text' => [
                'type' => 'divider',
                'label' => 'Comment body',
            ],

            'comments_body_size' => [
                'label' => 'Font size',
                'selector' => '.comment-body p',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-size',
            ],

            'comments_body_weight' => [
                'label' => 'Font weight',
                'selector' => '.comment-body p',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-weight',
            ],

            'comments_body_height' => [
                'label' => 'Line height',
                'selector' => '.comment-body p',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'line-height',
            ],

            'divide_comments_review-categories' => [
                'type' => 'divider',
                'label' => 'Review categories',
            ],

            'comments_review_cat_size' => [
                'label' => 'Font size',
                'selector' => '.rating-category-label',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-size',
            ],

            'comments_review_cat_weight' => [
                'label' => 'Font weight',
                'selector' => '.rating-category-label',
                // 'option' => 'options_form-heading-options_font-size',
                'type' => 'font-weight',
            ],



        ],
    ],

    'blog-fonts' => [
        'label' => 'Blog feed',
        'settings' => [

            'divide_archive_page_title' => [
                'type' => 'divider',
                'label' => 'Archive title',
            ],

            'blog_post_archive_title_size' => [
                'label' => 'Font size',
                'selector' => '.archive-heading h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'blog_post_archive_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.archive-heading h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'blog_post_archive_title_color' => [
                'label' => 'Color',
                'selector' => '.archive-heading h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],

            'divide_blog_post_feed_title' => [
                'type' => 'divider',
                'label' => 'Blog post feed title',
            ],

            'blog_post_feed_title_size' => [
                'label' => 'Font size',
                'selector' => '.sbf-title a',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'blog_post_feed_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.sbf-title a',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'blog_post_feed_title_line_height' => [
                'label' => 'Line height',
                'selector' => '.sbf-title a',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'blog_post_feed_title_color' => [
                'label' => 'Color',
                'selector' => '.sbf-title a',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],

            'divide_blog_post_feed_desc' => [
                'type' => 'divider',
                'label' => 'Blog post feed description',
            ],

            'blog_post_feed_desc_size' => [
                'label' => 'Font size',
                'selector' => '.sbf-title p',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'blog_post_feed_desc_weight' => [
                'label' => 'Font weight',
                'selector' => '.sbf-title p',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'blog_post_feed_desc_line_height' => [
                'label' => 'Line height',
                'selector' => '.sbf-title p',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'blog_post_feed_desc_color' => [
                'label' => 'Color',
                'selector' => '.sbf-title p',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],



        ],
    ],

    'single-post' => [
        'label' => 'Single post',
        'settings' => [

            'divide_single_post_title' => [
                'type' => 'divider',
                'label' => 'Post title',
            ],

            'single_post_title_size' => [
                'label' => 'Font size',
                'selector' => '.blogpost-section .blog-title h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.blogpost-section .blog-title h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_title_line_height' => [
                'label' => 'Line height',
                'selector' => '.blogpost-section .blog-title h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'single_post_title_color' => [
                'label' => 'Color',
                'selector' => '.blogpost-section .blog-title h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],

            'divide_single_post_details' => [
                'type' => 'divider',
                'label' => 'Post details',
            ],

            'single_post_details_size' => [
                'label' => 'Font size',
                'selector' => '.post-cover-buttons > ul > li > a, .post-cover-buttons > ul > li > div',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_details_weight' => [
                'label' => 'Font weight',
                'selector' => '.post-cover-buttons > ul > li > a, .post-cover-buttons > ul > li > div',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_details_color' => [
                'label' => 'Color',
                'selector' => '.post-cover-buttons > ul > li > a, .post-cover-buttons > ul > li > div',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],

            'divide_single_post_body' => [
                'type' => 'divider',
                'label' => 'Paragraphs',
            ],

            'single_post_paragraph_size' => [
                'label' => 'Font size',
                'selector' => '.blogpost-section .section-body p, .blogpost-section .section-body ul, .blogpost-section .section-body p, .blogpost-section .section-body ol',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_paragraph_weight' => [
                'label' => 'Font weight',
                'selector' => '.blogpost-section .section-body p, .blogpost-section .section-body ul, .blogpost-section .section-body p, .blogpost-section .section-body ol',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_paragraph_line_height' => [
                'label' => 'Line height',
                'selector' => '.blogpost-section .section-body p, .blogpost-section .section-body ul, .blogpost-section .section-body p, .blogpost-section .section-body ol',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'single_post_paragraph_color' => [
                'label' => 'Color',
                'selector' => '.blogpost-section .section-body p, .blogpost-section .section-body ul, .blogpost-section .section-body p, .blogpost-section .section-body ol',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],

            'divide_single_post_heading color' => [
                'type' => 'divider',
                'label' => 'Heading color',
            ],

            'single_post_heading_color' => [
                'label' => 'Color',
                'selector' => '.c27-content-wrapper h1,.c27-content-wrapper h2,.c27-content-wrapper h3,
                .c27-content-wrapper h4, .c27-content-wrapper h5, .c27-content-wrapper h6',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'color',
            ],

            'divide_single_post_h1' => [
                'type' => 'divider',
                'label' => 'Heading 1',
            ],

            'single_post_h1_size' => [
                'label' => 'Font size',
                'selector' => '.c27-content-wrapper h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_h1_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-content-wrapper h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_h1_line_height' => [
                'label' => 'Line height',
                'selector' => '.c27-content-wrapper h1',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'divide_single_post_h2' => [
                'type' => 'divider',
                'label' => 'Heading 2',
            ],

            'single_post_h2_size' => [
                'label' => 'Font size',
                'selector' => '.c27-content-wrapper h2',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_h2_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-content-wrapper h2',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_h2_line_height' => [
                'label' => 'Line height',
                'selector' => '.c27-content-wrapper h2',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'divide_single_post_h3' => [
                'type' => 'divider',
                'label' => 'Heading 3',
            ],

            'single_post_h3_size' => [
                'label' => 'Font size',
                'selector' => '.c27-content-wrapper h3',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_h3_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-content-wrapper h3',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_h3_line_height' => [
                'label' => 'Line height',
                'selector' => '.c27-content-wrapper h3',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'divide_single_post_h4' => [
                'type' => 'divider',
                'label' => 'Heading 4',
            ],

            'single_post_h4_size' => [
                'label' => 'Font size',
                'selector' => '.c27-content-wrapper h4',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_h4_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-content-wrapper h4',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_h4_line_height' => [
                'label' => 'Line height',
                'selector' => '.c27-content-wrapper h4',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'divide_single_post_h5' => [
                'type' => 'divider',
                'label' => 'Heading 5',
            ],

            'single_post_h5_size' => [
                'label' => 'Font size',
                'selector' => '.c27-content-wrapper h5',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_h5_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-content-wrapper h5',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_h5_line_height' => [
                'label' => 'Line height',
                'selector' => '.c27-content-wrapper h5',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],

            'divide_single_post_h6' => [
                'type' => 'divider',
                'label' => 'Heading 6',
            ],

            'single_post_h6_size' => [
                'label' => 'Font size',
                'selector' => '.c27-content-wrapper h6',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'single_post_h6_weight' => [
                'label' => 'Font weight',
                'selector' => '.c27-content-wrapper h6',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-weight',
            ],

            'single_post_h6_line_height' => [
                'label' => 'Line height',
                'selector' => '.c27-content-wrapper h6',
                // 'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'line-height',
            ],


        ],
    ],

    'user-dashboard' => [
        'label' => 'User dashboard',
        'settings' => [

            'divide_user_dashboard_menu' => [
                'type' => 'divider',
                'label' => 'Dashboard menu',
            ],

            'dash_menu_size' => [
                'label' => 'Font size',
                'selector' => '.woocommerce-MyAccount-navigation ul li a',
                'option' => 'options_dashboard-menu-options_font-size',
                'type' => 'font-size',
            ],

            'dash_menu_weight' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce-MyAccount-navigation ul li a',
                'option' => 'options_dashboard-menu-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_user_dashboard_block_title' => [
                'type' => 'divider',
                'label' => 'Dashboard block title',
            ],

            'dash_block_size' => [
                'label' => 'Font size',
                'selector' => '.woocommerce-MyAccount-content .element:not(.form-section) .title-style-1 h5',
                'option' => 'options_dashboard-block-title-options_font-size',
                'type' => 'font-size',
            ],

            'dash_block_weight' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce-MyAccount-content .element:not(.form-section) .title-style-1 h5',
                'option' => 'options_dashboard-block-title-options_font-weight',
                'type' => 'font-weight',
            ],
        ],
    ],

    'shop-settings' => [
        'label' => 'Shop page',
        'settings' => [

            'divide_catalog_title' => [
                'type' => 'divider',
                'label' => 'Catalog product title',
            ],

            'shop_catalog_title_size' => [
                'label' => 'Font size',
                'selector' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
                'option' => 'options_catalog-product-title-options_font-size',
                'type' => 'font-size',
            ],

            'shop_catalog_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
                'option' => 'options_catalog-product-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'shop_catalog_title_color' => [
                'label' => 'Color',
                'selector' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
                // 'option' => 'options_catalog-product-title-options_font-weight',
                'type' => 'color',
            ],

            'divide_catalog_price' => [
                'type' => 'divider',
                'label' => 'Catalog product price',
            ],

            'shop_catalog_price_size' => [
                'label' => 'Font size',
                'selector' => '.woocommerce ul.products li.product .price',
                // 'option' => 'options_catalog-product-title-options_font-size',
                'type' => 'font-size',
            ],

            'shop_catalog_price_weight' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce ul.products li.product .price',
                // 'option' => 'options_catalog-product-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'shop_catalog_price_color' => [
                'label' => 'Color',
                'selector' => '.woocommerce ul.products li.product .price',
                // 'option' => 'options_catalog-product-title-options_font-weight',
                'type' => 'color',
            ],

            'divide_product_title' => [
                'type' => 'divider',
                'label' => 'Single product page title',
            ],

            'product_title_size' => [
                'label' => 'Font size',
                'selector' => '.woocommerce div.product .product_title',
                'option' => 'options_single-catalog-product-title-options_font-size',
                'type' => 'font-size',
            ],

            'product_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce div.product .product_title',
                'option' => 'options_single-catalog-product-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'product_title_color' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce div.product .product_title',
                // 'option' => 'options_single-catalog-product-title-options_font-weight',
                'type' => 'color',
            ],

            'divide_product_description' => [
                'type' => 'divider',
                'label' => 'Single product description',
            ],

            'product_body_size' => [
                'label' => 'Font size',
                'selector' => '.woocommerce-product-details__short-description p, #tab-description p',
                // 'option' => 'options_single-catalog-product-title-options_font-size',
                'type' => 'font-size',
            ],

            'product_body_weight' => [
                'label' => 'Font weight',
                'selector' => '.woocommerce-product-details__short-description p, #tab-description p',
                // 'option' => 'options_single-catalog-product-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'product_body_height' => [
                'label' => 'Line height',
                'selector' => '.woocommerce-product-details__short-description p, #tab-description p',
                // 'option' => 'options_single-catalog-product-title-options_font-weight',
                'type' => 'line-height',
            ],

            'divide_single_product_price' => [
                'type' => 'divider',
                'label' => 'Single product price',
            ],

            'shop_single_price_size' => [
                'label' => 'Font size',
                'selector' => '.single-product div.product p.price .woocommerce-Price-amount',
                // 'option' => 'options_catalog-product-title-options_font-size',
                'type' => 'font-size',
            ],

            'shop_single_price_weight' => [
                'label' => 'Font weight',
                'selector' => '.single-product div.product p.price .woocommerce-Price-amount',
                // 'option' => 'options_catalog-product-title-options_font-weight',
                'type' => 'font-weight',
            ],

            'shop_single_price_color' => [
                'label' => 'Color',
                'selector' => '.single-product div.product p.price .woocommerce-Price-amount,
                .woocommerce div.product p.price del, .woocommerce div.product p.price ins',
                // 'option' => 'options_catalog-product-title-options_font-weight',
                'type' => 'color',
            ],


        ],
    ],

    'categories-widget' => [
        'label' => 'Categories widget',
        'settings' => [

            'divide_categories_default_title' => [
                'type' => 'divider',
                'label' => 'Category widget default template title',
            ],

            'categories_title_size' => [
                'label' => 'Font size',
                'selector' => '.listing-cat .lc-info h4',
                'option' => 'options_default-template-options_font-size',
                'type' => 'font-size',
            ],

            'categories_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.listing-cat .lc-info h4',
                'option' => 'options_default-template-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_categories_alternate_title' => [
                'type' => 'divider',
                'label' => 'Category widget alternate template title',
            ],

            'categories_alt_title_size' => [
                'label' => 'Font size',
                'selector' => '.one-region h2',
                'option' => 'options_alternate-template-options_font-size',
                'type' => 'font-size',
            ],

            'categories_alt_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.one-region h2',
                'option' => 'options_alternate-template-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_categories_cards_title' => [
                'type' => 'divider',
                'label' => 'Category widget cards template title',
            ],

            'categories_cards_title_size' => [
                'label' => 'Font size',
                'selector' => '.elementor-widget-case27-listing-categories-widget .ac-front-side .category-name',
                'option' => 'options_cards-template-options_font-size',
                'type' => 'font-size',
            ],

            'categories_cards_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.elementor-widget-case27-listing-categories-widget .ac-front-side .category-name',
                'option' => 'options_cards-template-options_font-weight',
                'type' => 'font-weight',
            ],

            'divide_categories_cards_alt_title' => [
                'type' => 'divider',
                'label' => 'Category widget cards alternate template title',
            ],

            'categories_cards_alt_title_size' => [
                'label' => 'Font size',
                'selector' => '.car-item-details h3',
                'option' => 'options_cards_alternate_options_font-size',
                'type' => 'font-size',
            ],

            'categories_cards_alt_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.car-item-details h3',
                'option' => 'options_cards_alternate_options_font-weight',
                'type' => 'font-weight',
            ],
        ],
    ],

    'wp-widget-styles' => [
        'label' => 'Footer and sidebar widgets',
        'settings' => [

            'divide_fs_widgets_title' => [
                'type' => 'divider',
                'label' => 'Widget title',
            ],

            'fs_widgets_title_size' => [
                'label' => 'Font size',
                'selector' => '.sidebar-widgets .c_widget .title-style-1 h5, .c_widget_title h5',
                // 'option' => 'options_default-template-options_font-size',
                'type' => 'font-size',
            ],

            'fs_widgets_title_weight' => [
                'label' => 'Font weight',
                'selector' => '.sidebar-widgets .c_widget .title-style-1 h5, .c_widget_title h5',
                // 'option' => 'options_default-template-options_font-weight',
                'type' => 'font-weight',
            ],

            'fs_widgets_title_color' => [
                'label' => 'Font weight',
                'selector' => '.sidebar-widgets .c_widget .title-style-1 h5, .c_widget_title h5',
                // 'option' => 'options_default-template-options_font-weight',
                'type' => 'color',
            ],

        ],
    ],

];