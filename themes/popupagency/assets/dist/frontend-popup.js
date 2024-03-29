var counters = 20;
MyListing.Datepicker = function(e, t) {
    this.el = jQuery(e), this.el.length && this.el.parent().hasClass("datepicker-wrapper") && (jQuery('<input type="text" class="display-value" readonly><i class="mi clear_all c-hide reset-value"></i>').insertAfter(this.el), this.el.attr("autocomplete", "off").attr("readonly", !0).addClass("picker"), this.parent = this.el.parent(), this.value = moment(this.el.val()), this.mask = this.parent.find(".display-value"), this.reset = this.parent.find(".reset-value"), this.args = jQuery.extend({
        timepicker: !1
    }, t), this.format = !0 === this.args.timepicker ? "YYYY-MM-DD HH:mm:ss" : "YYYY-MM-DD", this.displayFormat = !0 === this.args.timepicker ? CASE27.l10n.datepicker.dateTimeFormat : CASE27.l10n.datepicker.format, this.mask.attr("placeholder", this.el.attr("placeholder")), this.picker = this.el.daterangepicker({
        autoUpdateInput: !1,
        showDropdowns: !0,
        singleDatePicker: !0,
        timePicker24Hour: CASE27.l10n.datepicker.timePicker24Hour,
        locale: jQuery.extend({}, CASE27.l10n.datepicker, {
            format: this.format
        }),
        timePicker: this.args.timepicker
    }), this.picker.on("apply.daterangepicker", this.apply.bind(this)), this.el.on("change", this.change.bind(this)).trigger("change"), this.reset.click(function(e) {
        this.value = moment(""), this.el.trigger("change")
    }.bind(this)))
}, MyListing.Datepicker.prototype.apply = function(e, t) {
    this.value = t.startDate, this.el.trigger("change")
}, MyListing.Datepicker.prototype.change = function() {
    var e = this.value.isValid() ? this.value.format(this.format) : "",
    t = this.value.isValid() ? this.value.format(this.displayFormat) : "";
    this.el.val(e), this.mask.val(t), this.fireChangeEvent({
        value: e,
        mask: t
    }), "" === e ? this.reset.removeClass("c-show").addClass("c-hide") : this.reset.addClass("c-show").removeClass("c-hide")
}, MyListing.Datepicker.prototype.fireChangeEvent = function(e) {
    var t = document.createEvent("CustomEvent");
    t.initCustomEvent("datepicker:change", !1, !0, e), this.el.get(0).dispatchEvent(t)
}, jQuery(function(e) {
    e(".mylisting-datepicker").each(function(t, i) {
        var a = e(i).data("options");
        "object" != typeof a && (a = {}), new MyListing.Datepicker(i, a)
    })
}), MyListing.PhotoSwipe = function(e, t) {
    var i = document.querySelectorAll(".pswp")[0],
    a = {
        index: t,
        showAnimationDuration: 333,
        hideAnimationDuration: 333,
        showHideOpacity: !0,
        history: !1,
        shareEl: !1,
        getThumbBoundsFn: function(t) {
            var i = e[t].el,
            a = window.pageYOffset || document.documentElement.scrollTop,
            s = i.getBoundingClientRect();
            return {
                x: s.left,
                y: s.top + a,
                w: s.width
            }
        }
    };
    this.gallery = new PhotoSwipe(i, PhotoSwipeUI_Default, e, a), this.gallery.init(), this.gallery.listen("imageLoadComplete", this.lazyload.bind(this))
}, MyListing.PhotoSwipe.prototype.lazyload = function(e, t) {
    var i = this;
    if (t.w < 1 || t.h < 1) {
        var a = new Image;
        a.onload = function() {
            t.w = this.width, t.el.dataset.fullWidth = this.width, t.h = this.height, t.el.dataset.fullHeight = this.height, i.gallery.invalidateCurrItems(), i.gallery.updateSize(!0)
        }, a.src = t.src
    }
}, jQuery(function(e) {
    e("body").on("click", ".open-photo-swipe", function(e) {
        e.preventDefault(), new MyListing.PhotoSwipe([{
            src: this.href,
            w: this.dataset.fullWidth || 0,
            h: this.dataset.fullHeight || 0,
            el: this
        }], 0)
    }), e(".photoswipe-gallery .photoswipe-item").on("click", function(t) {
        t.preventDefault();
        var i = [],
        a = this,
        s = 0;
        e(this).parents(".photoswipe-gallery").find(".photoswipe-item").each(function(e, t) {
            i.push({
                src: t.href,
                w: t.dataset.fullWidth || 0,
                h: t.dataset.fullHeight || 0,
                el: t
            }), t == a && (s = e)
        }), new MyListing.PhotoSwipe(i, s)
    })
}), jQuery(function(e) {
    e(".quick-search-instance").each(function(i, a) {
        var s = {};
        s.el = e(this), s.input = s.el.find('input[name="search_keywords"]'), s.default = s.el.find(".default-results"), s.results = s.el.find(".ajax-results"), s.spinner = s.el.find(".loader-bg"), s.view_all = s.el.find(".all-results"), s.no_results = s.el.find(".no-results"), s.last_request = null, s.input.on("input", MyListing.Helpers.debounce(function(e) {
            t(s)
        }, 250)).trigger("input")
    });
    var t = function(t) {
        if (t.spinner.hide(), t.results.hide(), t.view_all.hide(), t.no_results.hide(), !t.input.val() || !t.input.val().trim()) return t.last_request && t.last_request.abort(), t.last_request = null, void t.default.show();
        t.default.hide(), t.spinner.show();
        var i = e.param({
            action: "mylisting_quick_search",
            security: CASE27.ajax_nonce,
            s: t.input.val().trim()
        });
        e.ajax({
            url: CASE27.ajax_url,
            type: "GET",
            dataType: "json",
            data: i,
            beforeSend: function(e) {
                t.last_request && t.last_request.abort(), t.last_request = e
            },
            success: function(e) {
                if (t.spinner.hide(), !e.content.trim().length) return t.no_results.show();
                t.results.html(e.content).show(), t.view_all.show()
            }
        })
    }
}), Vue.component("range-slider", {
    props: ["value", "type", "prefix", "suffix", "min", "max", "start", "end", "localize", "step"],
    template: '<div class="c27-range-slider-wrapper">\t\t\t\t<input type="hidden" class="slider-value" :value="value">\t\t\t\t<div class="amount">{{ displayValue }}</div>\t\t\t\t<div class="slider-range"></div>\t\t\t   </div>',
    mounted: function() {
        var e = this,
        t = "simple" != this.type || "min",
        i = parseInt(this.start, 10) ? parseInt(this.start, 10) : parseInt(this.min, 10),
        a = parseInt(this.end, 10) ? parseInt(this.end, 10) : parseInt(this.max, 10),
        s = isNaN(this.step) ? 1 : parseFloat(this.step),
        n = parseInt(this.min, 10),
        o = parseInt(this.max, 10),
        r = n + s,
        l = o - s,
        c = {
            range: t,
            min: n,
            max: o,
            step: s,
            slide: function(i, a) {
                "min" == t && (a.value > l && (a.value = o), e.$emit("input", a.value)), !0 === t && (a.values[0] < r && (a.values[0] = n), a.values[1] > l && (a.values[1] = o), e.$emit("input", a.values[0] + "::" + a.values[1]))
            }
        };
        if ("min" == t) {
            var d = parseInt(this.start, 10) ? parseInt(this.start, 10) : a;
            c.value = d, e.$emit("input", d)
        }!0 === t && (c.values = [i, a], e.$emit("input", i + "::" + a)), jQuery(this.$el).find(".slider-range").slider(c)
    },
    computed: {
        displayValue: function() {
            var e = "simple" != this.type || "min",
            t = this.prefix ? this.prefix : "",
            i = this.suffix ? this.suffix : "",
            a = "yes" === this.localize;
            if ("min" == e) {
                var s = this.value;
                return a && !isNaN(parseFloat(s)) && (s = (s = parseFloat(s)).toLocaleString()), t + s + i
            }
            if (!0 === e) {
                var n = ("" + this.value).split("::");
                return !a || isNaN(parseFloat(n[0])) || isNaN(parseFloat(n[1])) || (n[0] = parseFloat(n[0]), n[0] = n[0].toLocaleString(), n[1] = parseFloat(n[1]), n[1] = n[1].toLocaleString()), t + n[0] + i + " — " + t + n[1] + i
            }
        }
    }
}), MyListing.CustomSelect = function(e, t) {
    var i = this;
    if (this.el = jQuery(e), this.el.length) {
        if (this.el.addClass("mlduo-select"), this.el.data("placeholder")) var a = this.el.data("placeholder");
        else if (this.el.attr("placeholder")) a = this.el.attr("placeholder");
        else a = CASE27.l10n.selectOption;
        if (this.args = jQuery.extend({
            sortable: !0,
            selected: [],
            multiple: this.el.prop("multiple"),
            required: this.el.prop("required"),
            placeholder: a,
            ajax: !!this.el.data("mylisting-ajax")
        }, t), !0 === this.args.ajax) var s = "object" == typeof this.el.data("mylisting-ajax-params") ? this.el.data("mylisting-ajax-params") : {},
            n = {
                url: CASE27.ajax_url + "?action=" + this.el.data("mylisting-ajax-url"),
                dataType: "json",
                delay: 250,
                cache: !0,
                data: function(e) {
                    return s.page = e.page || 1, s.search = e.term, s.security = CASE27.ajax_nonce, s
                },
                processResults: function(e, t) {

                    return {
                        results: e.results || [],
                        pagination: {
                            more: e.more
                        }
                    }
                }
            };

            this.select = jQuery(e).select2({
                width: "100%",
                minimumResultsForSearch: 10,
                multiple: this.args.multiple,
                allowClear: !this.args.required,
                placeholder: this.args.placeholder,
                ajax: "object" == typeof n ? n : null,
                escapeMarkup: function(e) {
                    return e
                },
                createTag: function(e) {}
            });
            var o = this.el.next(".select2-container").first("ul.select2-selection__rendered");
            o.sortable({
                placeholder: "ui-state-highlight",
                forcePlaceholderSize: !0,
                items: "li:not(.select2-search__field)",
                tolerance: "pointer",
                stop: function() {
                    jQuery(o.find(".select2-selection__choice").get().reverse()).each(function() {
                        var e = jQuery(this).data("data").id,
                        t = i.el.find('option[value="' + e + '"]')[0];
                        i.el.prepend(t)
                    })
                }
            }), this.select.on("change", this.fireChangeEvent.bind(this))
        }
    }, MyListing.CustomSelect.prototype.fireChangeEvent = function(e) {
        var t = document.createEvent("CustomEvent");
        t.initCustomEvent("select:change", !1, !0, {
            value: jQuery(e.currentTarget).val()
        }), this.el.get(0).dispatchEvent(t)
    }, jQuery(function(e) {
        var t = {
            lastSearch: {},
            diacritics: {},
            stripDiacritics: function(e) {
                return e.replace(/[^\u0000-\u007E]/g, function(e) {
                    return t.diacritics[e] || e
                })
            }
        };

        function i() {
            e([".custom-select, .single-product .variations select", "#buddypress div.item-list-tabs#subnav ul li select", "#buddypress #notification-select", "#wc_bookings_field_resource", "#buddypress #messages-select", "#buddypress form#whats-new-form #whats-new-options select", ".settings.privacy-settings #buddypress #item-body > form > p select", ".woocommerce-ordering select", ".c27-submit-listing-form select:not(.ignore-custom-select)", ".ml-admin-listing-form select:not(.ignore-custom-select)"].join(", ")).each(function(e, t) {
                new MyListing.CustomSelect(t)
            })
        }
        e.fn.select2.defaults.defaults = e.fn.select2.defaults.defaults ? e.fn.select2.defaults.defaults : {
            language: {}
        }, e.fn.select2.defaults.defaults.language.errorLoading = function() {
            return CASE27.l10n.errorLoading
        }, e.fn.select2.defaults.defaults.language.loadingMore = function() {
            return CASE27.l10n.loadingMore
        }, e.fn.select2.defaults.defaults.language.noResults = function() {
            return CASE27.l10n.noResults
        }, e.fn.select2.defaults.defaults.language.searching = function(e) {
            return t.lastSearch = e, CASE27.l10n.searching
        }, e.fn.select2.amd.require(["select2/diacritics"], function(e) {
            t.diacritics = e
        }), e.fn.select2.defaults.defaults.sorter = function(i) {
            if ("" === e.trim(t.lastSearch.term)) return i;
            var a = i.slice(0);
            return term = t.lastSearch.term || "", term = t.stripDiacritics(term).toUpperCase(), a.sort(function(e, i) {
                var a = t.stripDiacritics(e.text).toUpperCase(),
                s = t.stripDiacritics(i.text).toUpperCase();
                return a.indexOf(term) - s.indexOf(term)
            }), a
        }, i(), e(document).on("mylisting:refresh-scripts", function() {
            i()
        }), e(".repeater").each(function(t, i) {
            $rep = e(i).repeater({
                initEmpty: !0,
                show: function() {
                    e(this).show(), e(this).find("select").select2({
                        minimumResultsForSearch: 0
                    })
                }
            }), $rep.setList(e(i).data("list"))
        }), e("body").on("select2:open", ".md-group select", function() {
            e(this).parents(".md-group").addClass("md-active")
        }), e("body").on("select2:close", ".md-group select", function() {
            if (e(this).val()) return e(this).parents(".md-group").addClass("md-active");
            e(this).parents(".md-group").removeClass("md-active")
        }), e(".md-group select").each(function(t, i) {
            if (e(this).val()) return e(this).parents(".md-group").addClass("md-active")
        })
    }), jQuery(window).on("load", function() {
        jQuery(window).on("resize", function() {
            window.innerWidth >= 1200 ? jQuery(".c27-packages .user-packages, .fc-one-column, .left-listings-locate, .more-filters-container, .finder-container .fc-default .finder-search, .finder-container .fc-default .finder-listings, .fc-type-2 .finder-search, .finder-tags, .quick-view-modal .grid-item .element, .quick-view-modal .grid-item").mCustomScrollbar({
                theme: "minimal-dark",
                scrollInertia: 300,
                mouseWheel: {
                    deltaFactor: 120,
                    scrollAmount: 280
                },
                updateOnSelectorChange: "true"
            }) : jQuery(".fc-one-column, .left-listings-locate, .more-filters-container, .finder-container > .finder-search, .finder-container > .finder-listings, .finder-tags, .quick-view-modal .grid-item .element, .quick-view-modal .grid-item").mCustomScrollbar("destroy")
        }).trigger("resize"), jQuery(".galleryPreview, .section-slider.owl-carousel").trigger("refresh.owl.carousel")
    }), jQuery("body").on("mouseover", ".c27-listing-preview-category-list > li:nth-child(2)", function() {
        jQuery(this).closest(".lf-item-container").parent("div").siblings().css("z-index", "2"), jQuery(this).closest(".lf-item-container").parent("div").css("z-index", "3")
    }), jQuery(document).ready(window.case27_ready_script = function(e) {
        e(document).trigger("mylisting:refresh-scripts"), e([".c27-main-header", ".finder-container", ".add-listing-step", ".hide-until-load"].join(", ")).css("opacity", 1), setTimeout(function() {
            e("#submit-job-form .wp-editor-wrap").css("height", "auto")
        }, 2500), "string" == typeof MyListing_Moment_Locale && MyListing_Moment_Locale.length && moment.locale(MyListing_Moment_Locale),
        function() {
            if (e("body").hasClass("add-listing-form")) {
                document.addEventListener("invalid", function(e) {
                    jQuery(e.target).addClass("invalid"), jQuery("html, body").animate({
                        scrollTop: jQuery(jQuery(".invalid")[0]).offset().top - 150
                    }, 0)
                }, !0), document.addEventListener("change", function(e) {
                    jQuery(e.target).removeClass("invalid")
                }, !0)
            }
        }(), jQuery("body").hasClass("elementor-editor-active") && (jQuery.fn.parallax = function() {});
        var t = e("#buddypress form#whats-new-form p.activity-greeting").text();
        if (jQuery("#whats-new-textarea textarea").attr("placeholder", t), e(".woocommerce-MyAccount-navigation ul").length && e(".woocommerce-MyAccount-navigation ul li.is-active, .woocommerce-MyAccount-navigation ul li.current-menu-item").length) {
            var i = e(".woocommerce-MyAccount-navigation ul li.is-active, .woocommerce-MyAccount-navigation ul li.current-menu-item").offset().left,
            a = e(".woocommerce-MyAccount-navigation ul").offset().left;
            i > a && e(".woocommerce-MyAccount-navigation ul").scrollLeft(i - a)
        }
        var s = null,
        n = 150,
        o = 0;

        function r() {
            var t = e(window).scrollTop(),
            i = e(window).height(),
            a = t + i;
            e(".reveal").each(function() {
                var i = e(this);
                if (!i.hasClass("reveal_visible")) {
                    var n = i.offset().top;
                    n <= a && (n + i.height() < t ? i.removeClass("reveal_pending").addClass("reveal_visible") : (i.addClass("reveal_pending"), s || requestAnimationFrame(l)))
                }
            }), e(".tab-pane.active").siblings().find(".reveal").removeClass("reveal_visible").removeClass("reveal_pending"), e(".section-slider .owl-item.active").siblings().find(".reveal").removeClass("reveal_visible").removeClass("reveal_pending")
        }

        function l() {
            s = null;
            var t = "undefined" != typeof performance ? performance.now() : Date.now();
            if (t - o > n) {
                o = t;
                var i = e(".reveal_pending");
                e(i.get(0)).removeClass("reveal_pending").addClass("reveal_visible")
            }
            e(".reveal_pending").length >= 1 && (s = requestAnimationFrame(l))
        }
        e(r), e(window).scroll(r), window.c27_trigger_reveal = r, e(window).on("resize", function() {
            if (window.innerWidth >= 993) {
                var t = e(".profile-menu").width(),
                i = e(".profile-menu").parent("div").width() - t - 141;
                e(".profile-header .profile-name").css("max-width", i)
            } else e(".profile-header .profile-name").css("cssText", "max-width: auto")
        }).trigger("resize"), e(".ph-details").each(function(t, i) {
            e(i).height() % 2 != 0 && e(i).height(e(i).height() + 1)
        }), e(".cat-card .ac-front-side .hovering-c").each(function(t, i) {
            e(i).height() % 2 != 0 && e(i).height(e(i).height() + 1)
        }), e(".mobile-menu").click(function(t) {
            t.preventDefault(), e(".i-nav").addClass("mobile-menu-open"), e("body").addClass("disable-scroll")
        }), e(".mnh-close-icon").click(function(t) {
            t.preventDefault(), e(".i-nav").removeClass("mobile-menu-open i-nav-fixed"), e("body").removeClass("disable-scroll"), e(window).resize()
        }), e(".i-nav-overlay").click(function() {
            e(this).siblings(".i-nav").removeClass("mobile-menu-open"), e("body").removeClass("disable-scroll")
        }), e(".main-nav li .submenu-toggle").click(function() {
            if (window.matchMedia("(max-width:1200px)").matches) {
                var t = e(this).siblings(".i-dropdown");
                t.hasClass("shown-menu") ? t.slideUp(300) : (t.slideDown(300), e(this).parent().parent().find("> li > .shown-menu").slideUp(300).removeClass("shown-menu")), t.toggleClass("shown-menu")
            }
        });
        var c, d, u = e(".pricing-item.featured");
        if (e(".pricing-item").hover(function() {
            e(u).removeClass("featured"), e(this).addClass("active")
        }, function() {
            e(this).removeClass("active"), e(u).addClass("featured")
        }), e('[data-toggle="tooltip"]').tooltip({
            trigger: "hover"
        }), e("body").on("hover", ".listing-feed-2", function(t) {
            e(this).find('[data-toggle="tooltip"]').tooltip({
                trigger: "hover"
            })
        }), e(document).mouseup(function(t) {
            var i = e(".finder-tags");
            e(".open-tags").click(function() {
                e(i).toggleClass("finder-tags-open")
            }), e(".close-tags").click(function() {
                e(i).removeClass("finder-tags-open")
            });
            var a = e(".finder-tags");
            a.is(t.target) || 0 !== a.has(t.target).length || e(a).removeClass("finder-tags-open")
        }), e(window).on("resize", function() {
            window.innerWidth < 1200 && (e(".finder-tabs .listing-cat-tab .listing-cat > a").on("click", function(t) {
                e(".finder-search").collapse("hide")
            }), e(".finder-tabs .tab-content .listing-type-filters .fc-search > a.buttons").on("click", function(t) {
                e(".finder-search").collapse("hide")
            }))
        }), e(".card-view a").on("click", function(t) {
            t.preventDefault(), e("body").css({
                overflow: "auto",
                height: "auto"
            }), e(".finder-listings").removeClass("fl-hidden"), e(".finder-map").removeClass("fm-visible")
        }), e(".toggle-search-type-2").on("click", function(t) {
            t.preventDefault(), e(".fc-type-2").toggleClass("fc-type-2-open")
        }), e(".fc-type-2 .finder-overlay").on("click", function() {
            e(".fc-type-2").removeClass("fc-type-2-open")
        }), e(".testimonial-carousel.owl-carousel").owlCarousel({
            mouseDrag: !1,
            items: 1,
            center: !0,
            autoplay: !0,
            dotsContainer: "#customDots"
        }), e(".testimonial-image").click(function(t) {
            t.preventDefault(), e(this).addClass("active").siblings().removeClass("active");
            var i = e(this).data("slide-no");
            e(".testimonial-carousel.owl-carousel").trigger("to.owl.carousel", i)
        }), e(".gallery-carousel").each(function(t, i) {
            var a = e(i).data("items") ? e(i).data("items") : 3,
            s = e(i).data("items-mobile") ? e(i).data("items-mobile") : 2;
            e(i).owlCarousel({
                margin: 10,
                items: a,
                mouseDrag: !1,
                responsive: {
                    0: {
                        items: s
                    },
                    600: {
                        items: a > 3 ? 3 : a
                    },
                    1000: {
                        items: a
                    }
                }
            })
        }), e(".gallery-prev-btn").click(function(t) {
            t.preventDefault(), e(this).parents(".element").find(".gallery-carousel.owl-carousel").trigger("prev.owl.carousel")
        }), e(".gallery-next-btn").click(function(t) {
            t.preventDefault(), e(this).parents(".element").find(".gallery-carousel.owl-carousel").trigger("next.owl.carousel")
        }), e(".full-screen-carousel .owl-carousel").owlCarousel({
            loop: !0,
            margin: 10,
            items: 2,
            center: !0,
            autoWidth: !0
        }), $sectionSlider = e(".section-slider.owl-carousel").owlCarousel({
            mouseDrag: !1,
            loop: !0,
            items: 1,
            animateOut: "fadeOut",
            callbacks: !0,
            nav: !0,
            autoHeight: !0,
            onInitialized: function() {
                this.refresh()
            }
        }), e("body:not(.elementor-editor-active) .elementor-section-use-parallax-yes, .parallax-bg, body:not(.elementor-editor-active) .elementor-widget-case27-page-heading-widget .elementor-widget-container").jarallax({
            speed: .2,
            disableParallax: function() {
                return !window.matchMedia("(min-width: 1200px)").matches || /Edge\/\d./i.test(navigator.userAgent) || /MSIE 9/i.test(navigator.userAgent) || /rv:11.0/i.test(navigator.userAgent) || /MSIE 10/i.test(navigator.userAgent)
            }
        }), c = null != navigator.userAgent.match(/Android/i), d = null != navigator.userAgent.match(/iPhone|iPad|iPod/i), c && e("body").addClass("smartphoneuser"), d && e("body").addClass("smartphoneuser iOSUser"), e(".section-slider.owl-carousel").on("changed.owl.carousel", function() {
            e(".section-slider .owl-item.active").siblings().find(".reveal").removeClass("reveal_visible").removeClass("reveal_pending"), setTimeout(function() {
                e(window).trigger("scroll")
            }, 500)
        }), e(".galleryPreview").owlCarousel({
            items: 1,
            center: !0,
            dotsContainer: "#customDots",
            autoHeight: !0
        }), e(".slide-thumb").click(function(t) {
            t.preventDefault();
            var i = e(this).data("slide-no");
            e(".galleryPreview.owl-carousel").trigger("to.owl.carousel", i)
        }), e(".gallery-thumb").each(function(t, i) {
            var a = e(i).data("items") ? e(i).data("items") : 4,
            s = e(i).data("items-mobile") ? e(i).data("items-mobile") : 2;
            e(i).owlCarousel({
                margin: 10,
                items: a,
                mouseDrag: !1,
                responsive: {
                    0: {
                        items: s
                    },
                    600: {
                        items: a > 3 ? 3 : a
                    },
                    1000: {
                        items: a
                    }
                }
            })
        }), e(".gallerySlider .gallery-prev-btn").click(function(t) {
            t.preventDefault(), e(".gallery-thumb.owl-carousel").trigger("prev.owl.carousel")
        }), e(".gallerySlider .gallery-next-btn").click(function(t) {
            t.preventDefault(), e(".gallery-thumb.owl-carousel").trigger("next.owl.carousel")
        }), e("body").hasClass("rtl")) var p = e(".grid").isotope({
    originLeft: !1
});
        else p = e(".grid").isotope();
        e(window).bind("load", function() {
            p.isotope("reloadItems").isotope()
        }), e(".explore-mobile-nav > ul li").on("click", function() {
            setTimeout(function() {
                p.isotope("reloadItems").isotope()
            }, 400)
        }), e(".tab-switch").click(function(t) {
            t.preventDefault(), e(this).tab("show"), setTimeout(function() {
                p.isotope("reloadItems").isotope()
            }, 400)
        }), e(".listing-feed-carousel").owlCarousel({
            loop: !0,
            margin: 20,
            items: 3,
            smartSpeed: 500,
            onDrag: function(t) {
                e(".listing-feed-carousel > .owl-item").css("opacity", "1")
            },
            onDragged: function(t) {
                e(".listing-feed-carousel > .owl-item").css("opacity", "0.4"), e(".listing-feed-carousel > .owl-item.active").css("opacity", "1")
            },
            responsive: {
                0: {
                    items: 1,
                    margin: 0
                },
                768: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        }), e(".listing-feed-next-btn").click(function(t) {
            t.preventDefault(), e(this).parents(".container").find(".listing-feed-carousel.owl-carousel").trigger("next.owl.carousel"), e(this).parents(".container").find(".listing-feed-carousel > .owl-item").css("opacity", "0.4"), e(this).parents(".container").find(".listing-feed-carousel > .owl-item.active").css("opacity", "1")
        }), e(".listing-feed-prev-btn").click(function(t) {
            t.preventDefault(), e(this).parents(".container").find(".listing-feed-carousel.owl-carousel").trigger("prev.owl.carousel"), e(this).parents(".container").find(".listing-feed-carousel > .owl-item").css("opacity", "0.4"), e(this).parents(".container").find(".listing-feed-carousel > .owl-item.active").css("opacity", "1")
        }), e(".featured-section-carousel").owlCarousel({
            loop: !0,
            margin: 0,
            items: 1,
            center: !0
        }), e(".listing-feed-next-btn").click(function(t) {
            t.preventDefault(), e(".featured-section-carousel.owl-carousel").trigger("next.owl.carousel")
        }), e(".listing-feed-prev-btn").click(function(t) {
            t.preventDefault(), e(".featured-section-carousel.owl-carousel").trigger("prev.owl.carousel")
        }), e(".lf-background-carousel").owlCarousel({
            margin: 20,
            items: 1,
            loop: !0
        }), e(".lf-background-carousel").each(function() {
            e(this).owlCarousel({
                margin: 20,
                items: 1,
                loop: !0
            }), e(this).on("prev.owl.carousel", function(e) {
                e.stopPropagation()
            }), e(this).on("next.owl.carousel", function(e) {
                e.stopPropagation()
            })
        }), e("body").on("click", ".lf-item-next-btn", function(t) {
            t.preventDefault(), e(this).parents(".lf-item").find(".lf-background-carousel.owl-carousel").trigger("next.owl.carousel")
        }), e("body").on("click", ".lf-item-prev-btn", function(t) {
            t.preventDefault(), e(this).parents(".lf-item").find(".lf-background-carousel.owl-carousel").trigger("prev.owl.carousel")
        }), e(".clients-feed-carousel").owlCarousel({
            loop: !0,
            margin: 20,
            items: 5,
            responsive: {
                0: {
                    items: 3
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        }), e(".clients-feed-next-btn").click(function(t) {
            t.preventDefault(), e(".clients-feed-carousel.owl-carousel").trigger("next.owl.carousel")
        }), e(".clients-feed-prev-btn").click(function(t) {
            t.preventDefault(), e(".clients-feed-carousel.owl-carousel").trigger("prev.owl.carousel")
        });
        var g = e(".header-gallery-carousel .item").length;
        e(".header-gallery-carousel").owlCarousel({
            items: Math.min.apply(Math, [3, g]),
            responsive: {
                0: {
                    items: Math.min.apply(Math, [1, g])
                },
                480: {
                    items: Math.min.apply(Math, [2, g])
                },
                992: {
                    items: Math.min.apply(Math, [3, g])
                }
            }
        }), e("body.logged-in .comment-info a").click(function(t) {
            t.preventDefault(), e(this).parents().siblings(".element").toggleClass("element-visible")
        }), e(window).scroll(function() {
            e(window).scrollTop() >= 800 ? e("a.back-to-top").css("opacity", "1") : e("a.back-to-top").css("opacity", "0")
        }).scroll(), e("a.back-to-top").click(function() {
            return e("html, body").animate({
                scrollTop: 0
            }, 1e3), !1
        }), e(".col-switch").click(function(t) {
            e(window).trigger("resize"), t.preventDefault();
            var i = e(this).data("no"),
            a = [];
            e(this).siblings().each(function() {
                a.push(e(this).data("no"))
            });
            var s = a[0],
            n = a[1];
            e(this).addClass("active").siblings().removeClass("active"), e(".finder-container").removeClass(s).removeClass(n).removeClass("opacity1"), e(".results-view").css("opacity", "0"), e(".fc-one-column .finder-search").css("opacity", "0"), e(".finder-container").addClass(i), setTimeout(function() {
                e(".finder-container").addClass("opacity1"), e(".lf-background-carousel").trigger("refresh.owl.carousel"), e(".grid").isotope().isotope("reloadItems").isotope()
            }, 400), e(".mapboxgl-map").length && MyListing.Maps.instances[0].map.resize()
        }), CASE27.smooth_scroll_enabled && e(window).on("resize", function() {
            window.innerWidth >= 1200 && e("html").easeScroll()
        }).trigger("resize"), jQuery(".ld-bookmark").click(function(e) {
            e.preventDefault(), jQuery(this).toggleClass("bookmarked")
        }), jQuery(".c27-quick-view-modal").on("hidden.bs.modal", function(t) {
            e(".c27-quick-view-modal .container").css("height", "auto")
        }), e("body").on("click", ".c27-toggle-quick-view-modal", function(t) {
            t.preventDefault(), e(".c27-quick-view-modal").modal("show"), e(".c27-quick-view-modal").addClass("loading-modal"), e.ajax({
                url: CASE27.ajax_url + "?action=get_listing_quick_view&security=" + CASE27.ajax_nonce,
                type: "POST",
                dataType: "json",
                data: {
                    listing_id: e(this).data("id")
                },
                success: function(t) {
                    e(".c27-quick-view-modal").removeClass("loading-modal"), e(".c27-quick-view-modal .modal-content").html(t.html), e(".c27-quick-view-modal .c27-map").css("height", e(".c27-quick-view-modal .modal-content").height()), e(window).trigger("resize"), setTimeout(function() {
                        new MyListing.Maps.Map(e(".c27-quick-view-modal .c27-map").get(0))
                    }, 10), e(".lf-background-carousel").owlCarousel({
                        margin: 20,
                        items: 1,
                        loop: !0
                    }), e(".c27-quick-view-modal .container").each(function(t, i) {
                        e(i).height() % 2 != 0 && e(i).height(e(i).height() + 1)
                    });
                    var i = e(".c27-quick-view-modal .modal-content").height();
                    e(".c27-quick-view-modal .block-map").css("height", i)
                }
            })
        }), e("body").on("click", ".header-search > input", function() {
            e(this).parent(".header-search").addClass("is-focused")
        }), e(document).on("mouseup touchend", function(t) {
            var i = e(".instant-results");
            i.is(t.target) || 0 !== i.has(t.target).length || e(".header-search").removeClass("is-focused")
        }), e(".c27-add-product-form input#_virtual").change(function(t) {
            e(".c27-add-product-form .product_shipping_class_wrapper")["checked" == e(this).attr("checked") ? "hide" : "show"]()
        }).change(), e(".c27-add-product-form input#_sale_price").keyup(function(t) {
            e(".c27-add-product-form ._sale_price_dates_from__wrapper")[e(this).val() ? "show" : "hide"](), e(".c27-add-product-form ._sale_price_dates_to__wrapper")[e(this).val() ? "show" : "hide"]()
        }).keyup(), e(".c27-add-product-form input#_manage_stock").change(function(t) {
            e(".c27-add-product-form ._stock__wrapper")["checked" == e(this).attr("checked") ? "show" : "hide"](), e(".c27-add-product-form ._backorders__wrapper")["checked" == e(this).attr("checked") ? "show" : "hide"]()
        }).change(), e(".slider-range.basic-form-slider-range").each(function(t, i) {
            var a = e(this),
            s = "simple" != a.data("type") || "min",
            n = parseInt(a.data("start"), 10) ? parseInt(a.data("start"), 10) : parseInt(a.data("min"), 10),
            o = parseInt(a.data("end"), 10) ? parseInt(a.data("end"), 10) : parseInt(a.data("max"), 10),
            r = isNaN(a.data("step")) ? 1 : parseFloat(a.data("step")),
            l = a.data("prefix") ? a.data("prefix") : "",
            c = a.data("suffix") ? a.data("suffix") : "",
            d = "yes" === a.data("localize"),
            u = !(!a.data("input-id") || !e("#" + a.data("input-id")).length) && e("#" + a.data("input-id")),
            p = !(!a.data("input-id") || !e("#" + a.data("input-id") + "__display").length) && e("#" + a.data("input-id") + "__display"),
            g = {
                range: s,
                min: parseInt(a.data("min"), 10),
                max: parseInt(a.data("max"), 10),
                step: r,
                slide: function(e, t) {
                    if ("min" == s && u.length) {
                        u.val(t.value);
                        var i = t.value;
                        p.length && (d && !isNaN(parseFloat(i)) && (i = (i = parseFloat(i)).toLocaleString()), p.val(l + i + c))
                    }
                    if (!0 === s && u.length) {
                        u.val(t.values[0] + "::" + t.values[1]);
                        var a = t.values[0],
                        n = t.values[1];
                        p.length && (!d || isNaN(parseFloat(a)) || isNaN(parseFloat(n)) || (a = (a = parseFloat(a)).toLocaleString(), n = (n = parseFloat(n)).toLocaleString()), p.val(l + a + c + " — " + l + n + c))
                    }
                }
            };
            if ("min" == s) {
                var h = parseInt(a.data("start"), 10) ? parseInt(a.data("start"), 10) : o;
                g.value = h, u.length && (u.val(h), p.length && (d && !isNaN(parseFloat(h)) && (h = (h = parseFloat(h)).toLocaleString()), p.val(l + h + c)))
            }!0 === s && (g.values = [n, o], u.length && (u.val(n + "::" + o), p.length && (!d || isNaN(parseFloat(n)) || isNaN(parseFloat(o)) || (n = (n = parseFloat(n)).toLocaleString(), o = (o = parseFloat(o)).toLocaleString()), p.val(l + n + c + " — " + l + o + c)))), a.slider(g)
        }), e(".woocommerce-MyAccount-navigation > ul").each(function() {
            e(this).children().length <= 6 && e(this).addClass("short")
        })
    }), jQuery(document).ready(function(e) {
        e(".main-loader").addClass("loader-hidden"), setTimeout(function() {
            e(".main-loader").hide()
        }, 600), e("body").addClass("c27-site-loaded"), e("header.header").parents("section.elementor-element").addClass("c27-header-element");
        var t, i = 0;
        e("header.header").length && (i = parseInt(e("header.header").innerHeight(), 10) + parseInt(e("header.header").offset().top, 10) + 1), e(".finder-container").css({
            top: "0px",
            height: "calc(100vh - " + i + "px)"
        }), e(".c27-open-popup-window, .cts-open-popup").click(function(e) {
            e.preventDefault();
            var t = screen.height / 2 - 200,
            i = screen.width / 2 - 300;
            window.open(this.href, "targetWindow", ["toolbar=no", "location=no", "status=no", "menubar=no", "scrollbars=yes", "resizable=yes", "width=600", "height=400", "top=" + t, "left=" + i].join(","))
        }), e("body").on("click", ".c27-bookmark-button, .mylisting-bookmark-item", function(t) {
            t.preventDefault();
            var i = e(this),
            a = i.data("label"),
            s = i.data("active-label");
            //if (!e("body").hasClass("logged-in")) return e("#sign-in-modal").modal("toggle");
            i.hasClass("bookmarking") || (i.addClass("bookmarking"), i.toggleClass("bookmarked"), i.find(".action-label").html(i.hasClass("bookmarked") ? s : a), e.ajax({
                type: "POST",
                url: CASE27.ajax_url + "?action=bookmark_listing",
                dataType: "json",
                data: {
                    listing_id: i.data("listing-id"),
                    c27_bookmark_nonce: i.data("nonce")
                },
                success: function(e, t, a) {
                    i.removeClass("bookmarking")
                }
            }))
        }), e(".c27-add-listing-review, .show-review-form, .pa-below-title .listing-rating").click(function(t) {
            t.preventDefault(), e(".toggle-tab-type-comments").first().click(), setTimeout(function() {
                e('#commentform textarea[name="comment"]').focus()
            }, 250)
        }), e(".c27-book-now").click(function(t) {
            t.preventDefault(), e(".toggle-tab-type-bookings").first().click()
        }), e(".c27-packages .user-packages .toggle-my-packages").on("click", function(t) {
            t.preventDefault(), e(this).parents(".user-packages").toggleClass("collapsed")
        }), e(".modal.c27-open-on-load").modal("show"), e(".c27-open-modal").click(function(t) {
            t.preventDefault();
            var i = e(this);
            e(".modal.in").one("hidden.bs.modal", function() {
                e(i.data("target")).modal("show")
            }).modal("hide")
        }), e(".featured-search .location-wrapper .geocode-location").click(function(t) {
            var i = e(this).siblings("input");
            MyListing.Geocoder.getUserLocation({
                receivedAddress: function(e) {
                    if (!e) return !1;
                    setTimeout(function() {
                        i.trigger("change")
                    }, 5), i.val(e.address)
                }
            })
        }), e("body").on("input change", '.md-group input[type="text"]', function() {
            if (e(this).val() && e(this).val().trim()) return e(this).parents(".md-group").addClass("md-active");
            e(this).parents(".md-group").removeClass("md-active")
        }), e('.md-group input[type="text"]').each(function(t, i) {
            if (e(this).val() && e(this).val().trim()) return e(this).parents(".md-group").addClass("md-active")
        }), e("body").on("change", '.c27-work-hours .day-wrapper .work-hours-type input[type="radio"]', function(t) {
            e(this).val();
            e(this).parents(".day-wrapper").removeClass(["day-status-enter-hours", "day-status-closed-all-day", "day-status-open-all-day", "day-status-by-appointment-only"].join(" ")).addClass("day-status-" + e(this).val())
        }),
    function() {
        var t = e("footer.footer"),
        i = e("#c27-site-wrapper");
        if (t.length && i.length) {
            var a = function() {
                t.outerHeight() > window.innerHeight ? (t.addClass("footer-large"), i.css("margin-bottom", 0)) : (t.removeClass("footer-large"), i.css("margin-bottom", t.outerHeight()))
            };
            a(), new ResizeSensor(t.get(0), MyListing.Helpers.debounce(a, 100))
        }
    }(), e("body:not(.logged-in) a.ml-login-form").click(function(t) {
        e("#sign-in-modal").length && (t.preventDefault(), e("#sign-in-modal").modal("toggle"))
    }), e("body.single-listing .tab-template-two-columns").each(function(t, i) {
        var a = e(this).find(".cts-column-wrapper.cts-main-column"),
        s = e(this).find(".cts-column-wrapper.cts-side-column"),
        n = a.find("> div").toArray(),
        o = s.find("> div").toArray(),
        r = window.matchMedia("(max-width: 991.5px)").matches ? "mobile" : "desktop",
        l = function(t) {
            var i = window.matchMedia("(max-width: 991.5px)").matches ? "mobile" : "desktop";
            if (i === r && !t) return !1;
            "mobile" === i ? n.forEach(function(t, i) {
                e(t).appendTo(a), o[i] && e(o[i]).appendTo(a)
            }) : o.forEach(function(t, i) {
                e(t).appendTo(s)
            }), r = i
        };
        l("mobile" === r), e(window).on("resize", MyListing.Helpers.debounce(function() {
            l()
        }, 300))
    }), t = function(e) {
        var t = e.parents(".pricing-item");
        if (!t.length) return !1;
        if (void 0 === t.data("selected")) return t.find('.owned-product-packages input[name="listing_package"]').first().prop("checked", !0), !0;
        var i = parseInt(t.data("selected"), 10);
        return t.find('.owned-product-packages input[name="listing_package"][value="' + i + '"]').prop("checked", !0), !0
    }, e('.cts-pricing-item input[name="listing_package"]').change(function(t) {
        var i = e(this).parents(".pricing-item");
        if (!i.length) return !0;
        i.data("selected", e(this).val())
    }), e(".cts-pricing-item .use-package-toggle").click(function(i) {
        t(e(this))
    }), e(".cts-pricing-item .select-plan:not(.cts-trigger-buy-new)").click(function(i) {
        i.preventDefault(), t(e(this)) && e("#job_package_selection").submit()
    }), e(".cts-pricing-item .cts-trigger-buy-new").click(function(t) {
        t.preventDefault();
        var i = e(this).parents(".pricing-item");
        if (!i.length) return !1;
        i.find("input.cts-buy-new").prop("checked", !0), e("#job_package_selection").submit()
    }), e(".cts-wcpl-package a.select-plan").on("click", function(t) {
        t.preventDefault(), e(this).siblings(".c27-job-package-radio-button").prop("checked", !0), e("#job_package_selection").submit()
    }), e("body.single-listing #c27-single-listing .wpcf7 noscript").detach(),
    function() {
        if (!e("#user-cart-menu").length) return !1;
        e(document.body).one("wc_fragments_loaded", function(t) {
            e("#user-cart-menu").addClass("user-cart-updated")
        })
    }(), e(document).on("mousedown click", ".c27-copy-link", function(t) {
        t.preventDefault();
        var i = e(this);
        if (!i.hasClass("copying")) {
            i.addClass("copying");
            var a = i.find("span"),
            s = a.html(),
            n = i.attr("href"),
            o = e("<input>");
            e("body").append(o), o.val(n).select(), document.execCommand("copy"), o.remove(), a.html(CASE27.l10n.copied_to_clipboard), setTimeout(function() {
                a.html(s), i.removeClass("copying")
            }, 1500)
        }
    }),
    function() {
        var t = e(".c27-main-header .logo"),
        i = e(".c27-main-header .header-right"),
        a = e(".c27-main-header .header-bottom"),
        s = e("body").hasClass("rtl"),
        n = window.matchMedia("(min-width:1200.5px)");

        function o() {
            if (!n.matches) return a.css("padding-left", ""), void a.css("padding-right", "");
            if (t.length) {
                var e = t.get(0).getBoundingClientRect().width + 30 + "px";
                a.css(s ? "padding-right" : "padding-left", e)
            }
            if (i.length) {
                e = i.get(0).getBoundingClientRect().width + 30 + "px";
                a.css(s ? "padding-left" : "padding-right", e)
            }
        }
        e(window).resize(MyListing.Helpers.debounce(o, 200)), o()
    }(),
    function() {
        var t = e(".c27-main-header");
        if (t.length && t.hasClass("header-fixed")) {
            var i = null,
            a = 0,
            s = t.outerHeight();
            e(window).on("scroll", MyListing.Helpers.debounce(function() {
                a = e(window).scrollTop(), i !== a && (a > s || a > s && null === i ? t.addClass("header-scroll") : t.removeClass("header-scroll"), a > s + 250 ? t.addClass("header-scroll-hide") : t.removeClass("header-scroll-hide"), a > s && a < i || null === i ? t.addClass("header-scroll-active") : t.removeClass("header-scroll-active"), i = a)
            }, 20))
        }
    }(), e(".modal-27").on("hide.bs.modal", function(t) {
        var i = e(this);
        i.hasClass("in") ? (t.preventDefault(), i.removeClass("in"), e("body").addClass("modal-closing"), setTimeout(function() {
            i.modal("hide")
        }, 200)) : e("body").removeClass("modal-closing")
    }),
    function() {
        var t = e(".add-listing-step");
        if (t.length && !e("body").hasClass("elementor-editor-active")) {
            t.appendTo("#c27-site-wrapper");
            var i = window.document.createEvent("UIEvents");
            i.initUIEvent("resize", !0, !1, window, 0), window.dispatchEvent(i)
        }
    }(), e("#submit-job-form").on("submit", function(t) {
        e(".add-listing-loader").show().removeClass("loader-hidden")
    }), e(".elementor-element[data-mylisting-link-to]").each(function() {
        var t = e(this).data("mylisting-link-to");
        if ("object" == typeof t && "undefined" !== t.url) {
            var i = e('<a class="mylisting-link-to"></a>');
            i.attr("href", t.url), t.is_external && i.attr("target", "_blank"), t.nofollow && i.attr("rel", "nofollow"), e(this).find(".elementor-column-wrap").append(i)
        }
    })
}), jQuery(document).ready(function(e) {
    if (!e("#commentform").length) return !1;
    e("#commentform")[0].encoding = "multipart/form-data", e("body").on("click", ".review-gallery-image-remove", function(t) {
        t.preventDefault(), e(this).parents(".review-gallery-image").remove()
    });
    e("#review-gallery-add-input").on("change", function() {
        e("#review-gallery-preview").html(""),
        function(t, a) {
            if (t.files) {
                var s = t.files.length;
                for (i = 0; i < s; i++) {
                    var n = new FileReader;
                    n.onload = function(t) {
                        var i = e('<div class="review-gallery-image"><span class="review-gallery-preview-icon"><i class="material-icons">file_upload</i></span></div>').css("background-image", "url('" + t.target.result + "')");
                        e(i).appendTo(a)
                    }, n.readAsDataURL(t.files[i])
                }
            }
        }(this, "#review-gallery-preview")
    })
}), jQuery(function(e) {
    MyListing.Dialog = function(t) {
        this.args = e.extend({
            message: "",
            status: "info",
            dismissable: !0,
            spinner: !1,
            timeout: 3e3
        }, t), this.show(), this.setTimeout()
    }, MyListing.Dialog.prototype.draw = function() {
        this.template = e(e("#mylisting-dialog-template").text()), this.template.addClass(this.args.status), this.insertContent(), this.template.appendTo("body")
    }, MyListing.Dialog.prototype.refresh = function(t) {
        this.args = e.extend(this.args, t), this.setTimeout(), this.insertContent()
    }, MyListing.Dialog.prototype.insertContent = function() {
        var e = this;
        this.template.find(".mylisting-dialog--message").html(this.args.message), this.template.find(".mylisting-dialog--dismiss")[this.args.dismissable ? "removeClass" : "addClass"]("hide").click(function(t) {
            t.preventDefault(), e.hide()
        }), this.template.find(".mylisting-dialog--loading")[this.args.spinner ? "removeClass" : "addClass"]("hide")
    }, MyListing.Dialog.prototype.setTimeout = function() {
        var e = this;
        e.timeout && clearTimeout(e.timeout), !isNaN(e.args.timeout) && e.args.timeout > 0 && (e.timeout = setTimeout(function() {
            e.hide()
        }, e.args.timeout))
    }, MyListing.Dialog.prototype.show = function() {
        var e = this;
        e.draw(), setTimeout(function() {
            e.template.addClass("slide-in")
        }, 15)
    }, MyListing.Dialog.prototype.hide = function() {
        var e = this;
        e.template.removeClass("slide-in").addClass("slide-out"), setTimeout(function() {
            e.template.remove()
        }, 250)
    }
}), 
(MyListing.Explore__wrapper = function() {
    document.getElementById("c27-explore-listings") && (MyListing.Explore = new Vue({
        el: "#c27-explore-listings",
        data: {
            state: {
                activeListingType: CASE27_Explore_Settings.ActiveListingType.slug,
                activeListingTypeData: {
                    name: CASE27_Explore_Settings.ActiveListingType.name,
                    icon: CASE27_Explore_Settings.ActiveListingType.icon
                },
                loading: !1,
                activeTab: "search-form",
                mobileTab: CASE27_Explore_Settings.ActiveMobileTab,
                lastRequest: null
            },
            mobile: window.matchMedia("screen and (max-width: 1200px)"),
            map: !1,
            facets: CASE27_Explore_Settings.Facets,
            listing_wrap: CASE27_Explore_Settings.ListingWrap,
            taxonomies: CASE27_Explore_Settings.Taxonomies
        },
        created: function() {
            this.state.activeTab = this.mobile.matches ? "search-form" : CASE27_Explore_Settings.ActiveTab, this.state.activeListingType && this.facets[this.state.activeListingType] && (this.facets[this.state.activeListingType].preserve_page = !0), this.jQueryReady()
        },
        mounted: function() {
            this.$nextTick(function() {
                this.getListings(), !1 === this._triggerLocationChange() && this._getListings()
            }.bind(this))
        },
        methods: {
            getListings: MyListing.Helpers.debounce(function(e) {
                this.mobile.matches || this._getListings(e)
            }, 250),
            _getListings: function(e) {
                console.log(e);
                if (this.facets[this.state.activeListingType].preserve_page || (this.facets[this.state.activeListingType].page = 0), void 0 !== this.taxonomies[this.state.activeTab]) var t = this.taxonomies[this.state.activeTab],
                    i = {
                        context: "term-search",
                        taxonomy: t.tax,
                        term: t.term,
                        page: t.page,
                        sort: this.facets[this.state.activeListingType].sort
                    };
                    else i = this.facets[this.state.activeListingType];

                    this.updateUrl(), this.state.loading = !0, this.$http.post(CASE27.ajax_url, {
                        form_data: i,
                        listing_type: this.state.activeListingType,
                        listing_wrap: this.listing_wrap
                    }, {
                        params: {
                            action: "get_listings",
                            security: CASE27.ajax_nonce
                        },
                        emulateJSON: !0,
                        before: function(e) {
                            // console.log(e);
                            this.state.lastRequest && this.state.lastRequest.abort(), this.state.lastRequest = e
                        }
                    }).then(function(t) {

                        this.updateView(t, e)
                    }.bind(this)).catch(function(e) {})
            },
            _getnewListings: function(e) {
                    //console.log(CASE27_Explore_Settings.Facets.rent);
                    alert("isime hai.");
                    if (this.facets[this.state.activeListingType].preserve_page || (this.facets[this.state.activeListingType].page = 0), void 0 !== this.taxonomies[this.state.activeTab]){ var t = this.taxonomies[this.state.activeTab],
                        i = {
                            context: "term-search",
                            taxonomy: t.tax,
                            term: t.term,
                            page: t.page,
                            sort: this.facets[this.state.activeListingType].sort
                        };
                    }
                    else{
                        i = this.facets[this.state.activeListingType];
                        var parameterName2 = 'region[]';
                        var mwburlparam = window.location.search.substring(1).split("&");
                        for( var j=0; j< mwburlparam.length; j++ ){
                            tmp = mwburlparam[j].split("=");
                            if(decodeURIComponent(tmp[0]) === parameterName2){
                                i.region.push(tmp[1]);
                                // console.log(tmp);
                            }
                        }
                    } 
                    this.state.loading = !0, this.$http.post(CASE27.ajax_url, {
                        form_data: i,
                        listing_type: this.state.activeListingType,
                        listing_wrap: this.listing_wrap
                    }, {
                        params: {
                            action: "get_listings",
                            security: CASE27.ajax_nonce
                        },
                        emulateJSON: !0,
                        before: function(e) {
                            this.state.lastRequest && this.state.lastRequest.abort(), this.state.lastRequest = e
                        }
                    }).then(function(t) {

                        this.updateView(t, e)
                            //this.updateMap()
                        }.bind(this)).catch(function(e) {})
                },
             getNearbyListings: function() {
                    var e = this;
                    if (void 0 !== this.facets[this.state.activeListingType].search_location && this.facets[this.state.activeListingType].search_location.length) this._getListings();
                    else {
                        var t = new MyListing.Dialog({
                            message: CASE27.l10n.nearby_listings_retrieving_location,
                            timeout: !1,
                            dismissable: !1,
                            spinner: !0
                        });
                        navigator.geolocation ? navigator.geolocation.getCurrentPosition(function() {
                            t.refresh({
                                spinner: !0
                            }), e.getUserLocation(function() {
                                t.refresh({
                                    message: CASE27.l10n.nearby_listings_searching,
                                    timeout: 2e3,
                                    spinner: !0,
                                    dismissable: !1
                                })
                            }, i)
                        }, i) : i()
                    }

                    function i() {
                        t.refresh({
                            message: CASE27.l10n.nearby_listings_location_required,
                            timeout: 4e3,
                            dismissable: !0,
                            spinner: !1
                        }), jQuery(".search-filters.type-" + e.state.activeListingType + ' input[name="search_location"]').focus().one("input", function() {
                            t.hide()
                        })
                    }
                },
             updateUrl: function() {
                    if (!window.history || CASE27_Explore_Settings.DisableLiveUrlUpdate) return !1;
                    var e = window.location.href.replace(window.location.search, ""),
                    t = this.facets[this.state.activeListingType],
                    i = {};
                    if (!window.location.search && CASE27_Explore_Settings.IsFirstLoad) return !1;
                    i.type = this.state.activeListingType, Object.keys(t).forEach(function(e) {
                        var a = t[e];
                        if (CASE27_Explore_Settings.FieldAliases[e]) var s = CASE27_Explore_Settings.FieldAliases[e];
                        else s = e;
                        if ("proximity_units" == e) return !1;
                        if (("search_location_lat" == e || "search_location_lng" == e) && a && void 0 !== t.search_location && t.search_location.length) {
                            s = "search_location_lat" == e ? "lat" : "lng";
                            var n = a.toString().indexOf("-") > -1 ? 9 : 8;
                            a = a.toString().substr(0, n)
                        }
                        return !!("proximity" != e || t.search_location_lat && t.search_location_lng) && (("_default" != e.substr(-8) || void 0 === t[e.substr(0, e.lastIndexOf("_default"))]) && ((!t[e + "_default"] || a != t[e + "_default"]) && ("page" === e && a > 0 && (a += 1, s = "pg"), void(a && void 0 !== a.length && a.length ? i[s] = a : "number" == typeof a && a && (i[s] = a)))))
                    }), window.history.replaceState(null, null, e + "?" + jQuery.param(i))
                },
             newupdateUrl: function(list) {
                var currentURL = window.location.href;

                // console.log(currentURL.indexOf(list)); 
                console.log(currentURL); 

                if(currentURL.indexOf('?') > -1 && currentURL.indexOf(list) < 0 ){
                    console.log("-->1");
                    window.history.replaceState(null, null, currentURL +'&region[]='+list);
                }
                else if(currentURL.indexOf('?') < 0 && currentURL.indexOf(list) < 0 ) {
                    console.log("-->2");
                    window.history.replaceState(null, null, currentURL + "?type=rent&sort=senaste&" + 'region[]='+list);
                }else if(currentURL.indexOf('?') > -1 && currentURL.indexOf(list) > -1){
                    console.log("-->3");
                    this.removeurlparameter(currentURL, list);
                }
                //this._getnewListings();
            },
          /*   _newupdateUrl: function(){
                    if (!window.history || CASE27_Explore_Settings.DisableLiveUrlUpdate) return !1;
                    var e = window.location.href,
                    t = this.facets[this.state.activeListingType],
                    i = {};
                    if (!window.location.search && CASE27_Explore_Settings.IsFirstLoad) return !1;
                    i.type = this.state.activeListingType, Object.keys(t).forEach(function(e) {
                        var a = t[e];
                        if (CASE27_Explore_Settings.FieldAliases[e]) var s = CASE27_Explore_Settings.FieldAliases[e];
                        else s = e;
                        if ("proximity_units" == e) return !1;
                        if (("search_location_lat" == e || "search_location_lng" == e) && a && void 0 !== t.search_location && t.search_location.length) {
                            s = "search_location_lat" == e ? "lat" : "lng";
                            var n = a.toString().indexOf("-") > -1 ? 9 : 8;
                            a = a.toString().substr(0, n)
                        }
                        return !!("proximity" != e || t.search_location_lat && t.search_location_lng) && (("_default" != e.substr(-8) || void 0 === t[e.substr(0, e.lastIndexOf("_default"))]) && ((!t[e + "_default"] || a != t[e + "_default"]) && ("page" === e && a > 0 && (a += 1, s = "pg"), void(a && void 0 !== a.length && a.length ? i[s] = a : "number" == typeof a && a && (i[s] = a)))))
                    }), window.history.replaceState(null, null, e + "?" + jQuery.param(i))
                },*/
             removeurlparameter: function(url, param){
                    var urlparts = url.split('?');   
                    if (urlparts.length >= 2) {
                        var content = decodeURIComponent('region[]');
                        console.log(content);
                        console.log(urlparts);
                        var prefix = content+'='+param;
                        var pars = urlparts[1].split(/[&;]/g);
                        
                        //reverse iteration as may be destructive
                        for (var i= pars.length; i-- > 0;) {    
                            //idiom for string.startsWith
                            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                                pars.splice(i, 1);
                            }
                        }

                        url= urlparts[0]+'?'+pars.join('&');
                        // return url;
                        window.history.replaceState(null, null, url);
                        // console.log(url);
                    } 
                },
             updateView: function(e, t) {
                    var i = e.body;
                    CASE27_Explore_Settings.IsFirstLoad = !1, this.facets[this.state.activeListingType].preserve_page = !1, jQuery(".finder-listings .results-view").length && jQuery(".finder-listings .results-view").html(i.html), jQuery(".fc-type-2-results").length && (jQuery(".fc-type-2-results").html(i.html), window.c27_trigger_reveal()), setTimeout(function() {
                        void 0 !== jQuery(".results-view.grid").data("isotope") && jQuery(".results-view.grid").isotope("destroy"), jQuery(".results-view.grid").isotope({
                            itemSelector: ".grid-item"
                        })
                    }, 10), jQuery(".lf-background-carousel").owlCarousel({
                        margin: 20,
                        items: 1,
                        loop: !0
                    }), jQuery('[data-toggle="tooltip"]').tooltip({
                        trigger: "hover"
                    }), jQuery(".c27-explore-pagination").length && jQuery(".c27-explore-pagination").html(i.pagination), jQuery(".fl-results-no span").length && jQuery(".fl-results-no span").html(i.showing), this.state.loading = !1, jQuery(".finder-container .fc-one-column").length && CASE27_Explore_Settings.ScrollToResults && jQuery(".finder-container .fc-one-column").mCustomScrollbar("scrollTo", ".finder-listings"), window.matchMedia("(min-width: 1200px)").matches ? (jQuery(".finder-container .fc-default .finder-listings").length && jQuery(".finder-container .fc-default .finder-listings").mCustomScrollbar("scrollTo", "top"), "pagination" == t && !CASE27_Explore_Settings.ScrollToResults && jQuery(".finder-container .fc-one-column").length && jQuery(".finder-container .fc-one-column").mCustomScrollbar("scrollTo", ".finder-listings"), jQuery(".fc-type-2-results").length && jQuery("html, body").animate({
                        scrollTop: jQuery("#c27-explore-listings").offset().top - 170
                    }, "slow")) : "pagination" === t && this._resultsScrollTop(), this.updateMap()

                    var parameterName1 = 'region[]';
                    var parameterName2 = 'tags[]';
                    var parameterName3 = 'category[]';
                    var parameterName4 = 'prioterad-pris';
                    var parameterName5 = 'yta';
                    var mwburlparam = window.location.search.substring(1).split("&");
                    var tags_count = 0;
                    var category_count = 0;
                    var region_count = 0;
                    for( var i=0; i< mwburlparam.length; i++ ){
                        tmp = mwburlparam[i].split("=");
                        if(decodeURIComponent(tmp[0]) === parameterName2){
                            tags_count++;
                        } else if(decodeURIComponent(tmp[0]) === parameterName3){
                            category_count++;
                        }else if(decodeURIComponent(tmp[0]) === parameterName1){
                            region_count++;
                        } else if(decodeURIComponent(tmp[0]) === parameterName4){
                            var selectedrange = decodeURIComponent(tmp[1]);
                            selectedrange = selectedrange.replace('::',' kr - ');
                            selectedrange = selectedrange+' kr';
                            console.log(selectedrange);
                            jQuery('body').find('.namn-Pris').text(selectedrange);
                        }
                        else if(decodeURIComponent(tmp[0]) === parameterName5){
                            var selectedmeter = decodeURIComponent(tmp[1]);
                            selectedmeter = selectedmeter.replace('::',' m² - ');
                            selectedmeter = selectedmeter+' m²';
                            jQuery('body').find('.namn-Yta').text(selectedmeter);
                        }
                    }
                    // console.log(tags_count);
                    var shtml = '<i class="fa fa-plus plus-sign"></i><i class="fa fa-minus minus-sign"></i>';

                    if(region_count == 1){

                        var newhtml = region_count+' '+frontend_popup_param.valda_plat+shtml;
                        jQuery(document).find('.namn-Plats').html(newhtml);
                    }else if(region_count > 1){
                        var newhtml = region_count+' '+frontend_popup_param.valda_plats+shtml;
                        jQuery(document).find('.namn-Plats').html(newhtml);
                    }
                    if(region_count == 0){
                        var newhtml = frontend_popup_param.plats+shtml;
                        jQuery(document).find('.namn-Plats').html(newhtml);
                    } 

                    if(category_count == 1){

                        var newhtml = category_count+' '+frontend_popup_param.vald_lokaltyp+shtml;
                        jQuery(document).find('.namn-Lokaltyp').html(newhtml);
                    }else if(category_count > 1){
                        var newhtml = category_count+' '+frontend_popup_param.valda_lokaltyper+shtml;
                        jQuery(document).find('.namn-Lokaltyp').html(newhtml);
                    }
                    if(category_count == 0){
                        var newhtml = frontend_popup_param.lokaltyp+shtml;
                        jQuery(document).find('.namn-Lokaltyp').html(newhtml);
                    } 

                    if(tags_count != 0){
                        var newhtml2 = tags_count+' '+frontend_popup_param.valda_amenities;
                        jQuery(document).find('.namn-Amenities').html(newhtml2);
                    }
                    else if(tags_count == 0){
                        var newhtml2 = frontend_popup_param.amenities;
                        jQuery(document).find('.namn-Amenities').html(newhtml2);
                    } 
                    // console.log(jQuery('body').find('.text-center').length);
                    var htmls = '<div class="col-md-12 text-center"><button class="btn mwb-loadmore-btn"><span id="text">'+frontend_popup_param.load+'</span> <i class="fa fa-arrow-circle-o-down"></i></button></div>';
                    if( jQuery('body').find('.text-center').length == 1 ){
                        jQuery('body').find('.results-view.grid').after(htmls);
                    }

                    size_li = jQuery('body').find('.my-listing-custom').size();
                    var x = 20;
                    if(counters > x){
                        x = counters;
                    }
                    
                    jQuery('body').find('.my-listing-custom:lt('+x+')').show();

                    jQuery('body').on('click','.btn',function () {
                        x = (x+20 <= size_li) ? x+20 : size_li;
                        // console.log(jQuery('body').find('.my-listing-custom:lt('+x+')'));
                        counters = x;
                        // MyListing.Explore.globalvalue(x);
                        jQuery('body').find('.my-listing-custom:lt('+x+')').show();
                        // MyListing.Explore._getListings();
                        jQuery(".results-view.grid").isotope({
                            itemSelector: ".grid-item"
                        });
                        if( counters == size_li ){
                            jQuery('body').find('.btn > #text').text('No More Listing');
                            jQuery('body').find('.btn').attr('disabled');
                        }
                        
                    });

                    jQuery('body').on('click','.show-map', function(){
                         jQuery(".results-view.grid").isotope({
                            itemSelector: ".grid-item"
                        });
                    });
                    
                },
             _resultsScrollTop: function() {
                    jQuery("html, body").animate({
                        scrollTop: jQuery("#c27-explore-listings").offset().top - 100
                    }, "slow")
                },
             setupMap: function() {
                    var e = jQuery(this.$el).find(".finder-map .map").attr("id");
                    if (!MyListing.Maps.getInstance(e)) return !1;
                    var t = MyListing.Maps.getInstance(e);
                    this.map = t.instance
                },
                updateMap: function() {
                    var e = this;
                    if (e.map) {
                        e.map.$el.removeClass("mylisting-map-loading"), e.map.removeMarkers(), e.map.trigger("updating_markers");
                        var t = new MyListing.Maps.LatLngBounds;
                        jQuery(this.$el).find(".results-view .lf-item-container").each(function(i, a) {
                            var s = jQuery(a);
                            if (s.data("latitude") && s.data("longitude")) {
                                var n = new MyListing.Maps.Marker({
                                    position: new MyListing.Maps.LatLng(s.data("latitude"), s.data("longitude")),
                                    map: e.map,
                                    popup: new MyListing.Maps.Popup({
                                        content: '<div class="lf-item-container lf-type-2">' + s.html() + "</div>"
                                    }),
                                    template: {
                                        type: "advanced",
                                        thumbnail: s.data("thumbnail"),
                                        icon_name: s.data("category-icon"),
                                        icon_background_color: s.data("category-color"),
                                        icon_color: s.data("category-text-color"),
                                        listing_id: s.data("id")
                                    }
                                });
                                e.map.markers.push(n), t.extend(n.getPosition())
                            }
                        }), e.map.fitBounds(t), e.map.getZoom() > 17 && e.map.setZoom(17), e.map.markers.length < 1 && (e.map.setZoom(2), e.map.setCenter(new MyListing.Maps.LatLng(33.9, 27.8))), e.map.trigger("updated_markers")
                    } else if (document.getElementsByClassName("finder-map").length) var i = setInterval(function() {
                        e.map && (clearInterval(i), e.updateMap())
                    }, 200)
                },
                    getUserLocation: MyListing.Helpers.debounce(function(e, t) {
                        var i = this;
                        MyListing.Geocoder.setMap(this.map), MyListing.Geocoder.getUserLocation({
                            receivedCoordinates: function(e) {
                                i.facets[i.state.activeListingType].search_location_lat = e.coords.latitude, i.facets[i.state.activeListingType].search_location_lng = e.coords.longitude
                            },
                            receivedAddress: function(t) {
                                i.$set(i.facets[i.state.activeListingType], "search_location", t.address), setTimeout(function() {
                                    i._triggerLocationChange()
                                }, 5), "function" == typeof e && e()
                            },
                            geolocationFailed: function() {
                                "function" == typeof t && t()
                            }
                        })
                    }, 250),
                    geocodeLocation: MyListing.Helpers.debounce(function(e) {
                        var t = this;

                        function i(e) {
                            t.facets[t.state.activeListingType].search_location_lat = e.latitude, t.facets[t.state.activeListingType].search_location_lng = e.longitude, t.facets[t.state.activeListingType].search_location = e.address, t._getListings()
                        }
                        return e.target.value.length ? e.detail.place && e.detail.place.address && e.detail.place.latitude && e.detail.place.longitude ? i(e.detail.place) : void MyListing.Geocoder.geocode(e.target.value, function(t) {
                            if (t) return t.address = e.target.value, i(t)
                        }) : i({
                            latitude: !1,
                            longitude: !1,
                            address: ""
                        })
                }, 300),
                    _triggerLocationChange: function() {
                        var e = jQuery(".search-filters.type-" + this.state.activeListingType + " .form-location-autocomplete");
                        return e.each(function(e, t) {
                            var i = document.createEvent("CustomEvent");
                            i.initCustomEvent("autocomplete:change", !1, !0, {
                                place: {
                                    latitude: this.facets[this.state.activeListingType].search_location_lat,
                                    longitude: this.facets[this.state.activeListingType].search_location_lng,
                                    address: this.facets[this.state.activeListingType].search_location
                                }
                            }), t.dispatchEvent(i), jQuery(t).trigger("change")
                        }.bind(this)), !!e.length
                    },
                    _handleTermSelect: function(e, t, i) {
                        this.facets[i][t] = e.detail.value, this.getListings(), this._maybeAddChildSelect({
                            index: 0,
                            select: jQuery(e.target),
                            wrapper: jQuery(e.target).parents(".explore-filter").next(),
                            field: t,
                            listing_type: i
                        })
                    },
                    _maybeAddChildSelect: function(e) {
                        var t = this,
                        i = ".term-select.term-select-" + e.index + ", .term-select.term-select-" + e.index + " ~ .term-select";
                        e.wrapper.find(i).find("select").select2("destroy"), e.wrapper.find(i).remove(), e.select.val() && jQuery.ajax({
                            url: CASE27.ajax_url + "?action=mylisting_list_terms",
                            type: "GET",
                            dataType: "json",
                            data: jQuery.extend({}, e.select.data("mylisting-ajax-params"), {
                                page: 1,
                                security: CASE27.ajax_nonce,
                                parent: e.select.val(),
                                search: "tests"
                            }),
                            beforeSend: function(t) {
                                e.select.data("last_request") && e.select.data("last_request").abort(), e.select.data("last_request", t)
                            },
                            success: function(i) {
                                "object" == typeof i && i.results && i.results.length && t._addChildSelect(e)
                            }
                        })
                    },
                    _addChildSelect: function(e) {
                        var t = this,
                        i = jQuery('<div class="select-wrapper term-select term-select-' + e.index + '">                    <select class="custom-select term-select" data-mylisting-ajax="true" data-mylisting-ajax-url="mylisting_list_termss">                        <option></option>                    </select>                </div>'),
                        a = CASE27.l10n.all_in_category.replace("%s", e.select.find('option[value="' + e.select.val() + '"]').text()),
                        s = jQuery.extend({}, e.select.data("mylisting-ajax-params"));
                        s.parent = e.select.val(), i.find("select").data("mylisting-ajax-params", s).attr("placeholder", a), e.wrapper.append(i), new MyListing.CustomSelect(i.find("select")), i.find("select").on("select:change", function(i) {
                            t.facets[e.listing_type][e.field] = i.detail.value || e.select.val(), t.getListings(), t._maybeAddChildSelect({
                                index: e.index + 1,
                                select: jQuery(i.target),
                                wrapper: e.wrapper,
                                field: e.field,
                                listing_type: e.listing_type
                            })
                        })
                    },
                    resetFilters: function(e) {

                        if (e && e.target) {
                            var t = jQuery(e.target).find("i");
                            t.removeClass("fa-spin"), setTimeout(function() {
                                t.addClass("fa-spin")
                            }, 5)
                        }
                        var i = this.state.activeListingType,
                        a = this.facets[i];
                        var mwbcustomuri = window.location.toString();
                        if (mwbcustomuri.indexOf("?") > 0) {
                            var mwb_custom_clean_uri = mwbcustomuri.substring(0, mwbcustomuri.indexOf("?"));
                            window.history.replaceState({}, document.title, mwb_custom_clean_uri);
                            this.getListings();
                        }
                        jQuery(".search-filters.type-" + i + " .filter-wrapper > .form-group").each(function() {
                            var e = jQuery(this),
                            t = jQuery(this).data("key");
                            
                            if (e.hasClass("wp-search-filter") && e.find('input[name="search_keywords"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("location-filter") && e.find('input[name="search_location"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("text-filter") && e.find('input[type="text"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("checkboxes-filter") && (a[t] = Array.isArray(a[t]) ? [] : ""), e.hasClass("date-filter") && e.find(".datepicker-wrapper .reset-value").click(), e.hasClass("dateyear-filter") && e.find(".custom-select").val([]).trigger("change").trigger("select2:close"), e.hasClass("dropdown-filter") && e.find(".custom-select").val([]).trigger("change").trigger("select2:close"), e.hasClass("range-filter")) {
                                var i = e.find(".slider-range"),
                                s = i.slider("option");
                                "simple" === e.data("type") ? i.slider("value", s.max) : i.slider("values", [s.min, s.max]), i.slider("option", "slide").apply(i, [null, {
                                    value: s.max,
                                    values: [s.min, s.max]
                                }])
                            }
                        })

                        jQuery(".mwb_custom_filters > .form-group").each(function() {
                            var e = jQuery(this),
                            t = jQuery(this).data("key");
                            
                            if (e.hasClass("wp-search-filter") && e.find('input[name="search_keywords"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("location-filter") && e.find('input[name="search_location"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("text-filter") && e.find('input[type="text"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("checkboxes-filter") && (a[t] = Array.isArray(a[t]) ? [] : ""), e.hasClass("date-filter") && e.find(".datepicker-wrapper .reset-value").click(), e.hasClass("dateyear-filter") && e.find(".custom-select").val([]).trigger("change").trigger("select2:close"), e.hasClass("dropdown-filter") && e.find(".custom-select").val([]).trigger("change").trigger("select2:close"), e.hasClass("range-filter")) {
                                var i = e.find(".slider-range"),
                                s = i.slider("option");
                                "simple" === e.data("type") ? i.slider("value", s.max) : i.slider("values", [s.min, s.max]), i.slider("option", "slide").apply(i, [null, {
                                    value: s.max,
                                    values: [s.min, s.max]
                                }])
                            }
                        });

                        jQuery('body').find('.namn-Pris').text('Pris');
                        jQuery('body').find('.namn-Yta').text('Yta');

                    },
                    jQueryReady: function() {
                        var e = this;
                        jQuery(function(t) {
                            jQuery("body").on("click", ".c27-explore-pagination a", function(t) {
                                t.preventDefault();
                                var i = "pagination";
                                e.state.activeListingType && "search-form" == e.state.activeTab && (e.facets[e.state.activeListingType].page = parseInt(jQuery(this).data("page"), 10) - 1, e.facets[e.state.activeListingType].preserve_page = !0, e._getListings(i)), e.state.activeTab && void 0 !== e.taxonomies[e.state.activeTab] && (e.taxonomies[e.state.activeTab].page = parseInt(jQuery(this).data("page"), 10) - 1, e._getListings(i))
                            }), jQuery(".col-switch").click(function(t) {
                                e.map.trigger("resize")
                            }), jQuery("body").on("mouseenter", ".results-view .lf-item-container.listing-preview", function() {
                                jQuery(".marker-container .marker-icon." + jQuery(this).data("id")).addClass("active")
                            }), jQuery("body").on("mouseleave", ".results-view .lf-item-container.listing-preview", function() {
                                jQuery(".marker-container .marker-icon." + jQuery(this).data("id")).removeClass("active")
                            }), t(".cts-explore-sort.cts-sort-type-" + e.state.activeListingType).find(".toggle-rating .trigger-proximity-order.selected").length && setTimeout(function() {
                                e.getNearbyListings()
                            }, 300), t(".main-term select").trigger("change")
                        })
                    }
                },
                watch: {
                    "state.activeListingType": function() {
                        this.state.activeListingType && (this._getListings(), this._triggerLocationChange())
                    },
                    "state.activeTab": function() {
                        this.state.activeTab && void 0 !== this.taxonomies[this.state.activeTab] && this.getListings()
                    },
                    "state.mobileTab": function() {
                        "map" === this.state.mobileTab && setTimeout(function() {
                            this.map.trigger("refresh")
                        }.bind(this), 5)
                    }
                }
            }))
})();
//# sourceMappingURL=frontend.js.map

// jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
//     var $response = ""+xhr.responseText+"";

//     $response = jQuery.parseJSON($response);
//     if( $response.search_region != 'undefined' && $response.search_region == '1'){
//         var $htmldata = '';
//         var region_array = {};
//         var city_array = {};
//         var subcity_array = {};
//         jQuery(document).find('.select2-dropdown').css('display','none','important');

//         $htmldata += '<div class="mwb-filter-plat">';
//         $htmldata += '<div class="mwb-filter-plat__region"><ul class="mwb-filter-plat__tab">';

//         var keys = '';
//         if($response.results != 'undefined'){
//             jQuery.each($response.results, function(i, v){
//                 if(v.text.indexOf('&mdash; ') != -1 && v.text.indexOf('&mdash; &mdash; ') ){
//                  city_array[v.id] = v.text;
//              }else if( v.text.indexOf('&mdash; &mdash; ') != -1  ){
//               subcity_array[v.id] = v.text;
//           }else{
//             region_array[v.id] = v.text;
//         }
//     });

//             var data = [];
//             var data2 = [];
//             jQuery.each($response.results, function(all_key, all_val){
//                 if(all_val.text.indexOf('&mdash; &mdash; ') != -1 && all_val.parent in city_array){
//                     data.push($response.results[all_key]);
//                 }else if(all_val.text.indexOf('&mdash; ') != -1 && all_val.parent in region_array){
//                     data2.push($response.results[all_key]);
//                 }
//             });
//         }

//         // console.log(data2);

//         if( !jQuery.isEmptyObject(region_array) ){
//             jQuery.each(region_array,function(reg_ind, reg_val){
//                 $htmldata += '<li><a href="#'+reg_ind+'" data-region="'+reg_ind+'">'+reg_val+'</a></li>';
//             });
//         }

//         $htmldata += '</ul></div>';
//         var $htmldatacitysub = '';
//         var $htmldatacity = '';

//         if(!jQuery.isEmptyObject(region_array) && !jQuery.isEmptyObject(city_array)){

//             jQuery.each(region_array, function(id, reg_v){
//                 $htmldatacity = '';
//                 $htmldatacity += '<div id="'+id+'" class="mwb-filter-plat__con">';
//                 $htmldatacity += '<h2>'+reg_v+'</h2>';
//                 $htmldatacity += '<div class="mwb-filter-plat__cat"><ul>';
//                 jQuery.each($response.results, function(city_id, city_val){
//                     if(city_val.parent == id && city_val.text.indexOf('&mdash; ') != -1){
//                         var string = city_val.text.replace('&mdash; ','');
//                         var countercity = '';
//                         jQuery.each(data2, function(count_ind, count_val){
//                             if(count_val.id == city_val.id){
//                                 countercity = count_val.count;
//                             }
//                         });
//                         // console.log(countercity);
//                         $htmldatacity += '<li><a href="#'+city_val.id+'" data-cat="'+city_val.id+'">'+string+' ('+countercity+')</a></li>';
//                     }
//                 });
//                 $htmldatacity += '</ul></div>';
//                 jQuery.each($response.results,function(ci, cv){
//                     if(cv.parent == id && cv.text.indexOf('&mdash; ') != -1){
//                         var strings = cv.text.replace('&mdash; ','');
//                         $htmldatacity += '<a class="mwb-mobile-nav" href="#'+cv.id+'" data-cat="'+cv.id+'">'+strings+'</a>';
//                         $htmldatacity += '<div class="mwb-filter-plat__sub" id="'+cv.id+'">';
//                         $htmldatacity += '<div class="mwb_custom_headings"><input class="mwb_custom_checkbox" data-name="'+cv.id+'" id="'+cv.id+'1" type="checkbox"><label class="mwb-filter-platsub_heading" for="'+cv.id+'1"> Hela '+strings+'</label></div>';

//                         jQuery.each(data, function(lastkey, lastvalue){
//                             if( lastvalue.parent == cv.id && lastvalue.text.indexOf('&mdash; &mdash; ') != -1 ){
//                                 var string_sub = lastvalue.text;
//                                 string_sub = string_sub.replace('&mdash; &mdash; ','');;
//                                 $htmldatacity += '<div class="mwb-filter-plat__sub-con"><input class="mwb_custom_checkbox" type="checkbox" id="'+lastvalue.id+'"><label for="'+lastvalue.id+'">'+string_sub+'</label><span> ('+lastvalue.count+') </span></div>';
//                             }
//                         });
//                         $htmldatacity += '</div>';
//                     }
//                 });
//                 // console.log(data);

//                 $htmldatacity += '</div>';
//                 $htmldata += $htmldatacity;
//             });
//         }

//         // if( data.length > 0){
//         //     jQuery.each(city_array,function(ci, cv){
//         //         var newcity = cv.replace('&mdash; ','');
//         //         $htmldata += '<a class="mwb-mobile-nav" href="#'+ci+'" data-cat="'+ci+'">'+newcity+'</a>';
//         //         $htmldata += '<div class="mwb-filter-plat__sub" id="'+ci+'">';
//         //         $htmldata += '<div class="mwb_custom_headings"><input class="mwb_custom_checkbox" data-name="'+ci+'" id="'+ci+'1" type="checkbox"><label class="mwb-filter-platsub_heading" for="'+ci+'1"> Hela '+newcity+'</label></div>';
//         //         jQuery.each($response.results, function(lastkey, lastvalue){
//         //             if( lastvalue.parent == ci ){
//         //                 var string_sub = lastvalue.text;
//         //                 string_sub = string_sub.replace('&mdash; &mdash; ','');;
//         //                 $htmldata += '<div class="mwb-filter-plat__sub-con"><input class="mwb_custom_checkbox" type="checkbox" id="'+lastvalue.id+'"><label for="'+lastvalue.id+'">'+string_sub+'</label><span> ('+lastvalue.count+') </span></div>';
//         //             }
//         //         })
//         //         $htmldata += '</div>';
//         //     });
//         // }

//         $htmldata += '<div class="mwb-button"><input id="mwb_search_plats" type="button" value="Okej"></div>';
//         if(jQuery(document).find('.mwb-filter-plat').length <= 0 ){
//             jQuery(document).find('.main-term').after($htmldata);
//         }

//         jQuery('body').find('.mwb-filter-plat__con').first().addClass('active');
//        // jQuery('body').find('.mwb-filter-plat__sub').first().addClass('active');


//         //Query('body').find('.mwb-filter-plat__tab a').first().addClass('active');
//         //jQuery('body').find('.mwb-filter-plat__cat a').first().addClass('active');


//         var region = [];

//         jQuery('body').on('click','.mwb_custom_checkbox', function(){
//             if(!jQuery(this).parent().hasClass('mwb_custom_headings')){
//                 var mwb_region_name = jQuery(this).attr('id');
//             }else{
//                 var mwb_region_name = jQuery(this).attr('data-name');
//             }
//             MyListing.Explore.newupdateUrl(mwb_region_name);
//         });

//         var parameterName = 'region[]';
//         var mwb_result = [],
//         tmp = [];
//         var urlparam = window.location.search.substring(1).split("&");

//         for( var i=0; i< urlparam.length; i++ ){
//             tmp = urlparam[i].split("=");
//             if (decodeURIComponent(tmp[0]) === parameterName) mwb_result.push(decodeURIComponent(tmp[1]));
//         }

//         jQuery('body').find('.mwb_custom_checkbox').each(function(){

//             if( jQuery.inArray(jQuery(this).attr('id'),mwb_result) > -1 ){
//                 jQuery(this).attr("checked", "checked");
//             }
//             else if( jQuery.inArray(jQuery(this).attr('data-name'),mwb_result) > -1 ){
//                 jQuery(this).attr("checked", "checked");
//             }
//         });
//     } 


// });

jQuery(document).ready(function(){
    var region = [];
    
    jQuery('body').on('click','.mwb_custom_checkbox', function(){
        if(!jQuery(this).parent().hasClass('mwb_custom_headings')){
            var mwb_region_name = jQuery(this).attr('id');
        }else{
            var mwb_region_name = jQuery(this).attr('data-name');
        }
        console.log("MyListing", MyListing);
        console.log("MyListing.Explore", MyListing.Explore);
        
        //setTimeout(function(){ alert("Hello"); }, 3000);
        //MyListing.Explore.newupdateUrl(mwb_region_name);
        cstmnewupdateUrl(mwb_region_name)
    });

    var parameterName = 'region[]';
    var mwb_result = [],
    tmp = [];
    var urlparam = window.location.search.substring(1).split("&");

    for( var i=0; i< urlparam.length; i++ ){
        tmp = urlparam[i].split("=");
        if (decodeURIComponent(tmp[0]) === parameterName) mwb_result.push(decodeURIComponent(tmp[1]));
    }
    
    jQuery('body').find('.mwb_custom_checkbox').each(function(){

        if( jQuery.inArray(jQuery(this).attr('id'),mwb_result) > -1 ){
            jQuery(this).attr("checked", "checked");
        }
        else if( jQuery.inArray(jQuery(this).attr('data-name'),mwb_result) > -1 ){
            jQuery(this).attr("checked", "checked");
        }
    });
});

function cstmnewupdateUrl(list)
{
    var currentURL = window.location.href;

    // console.log(currentURL.indexOf(list)); 
    console.log(currentURL); 

    if(currentURL.indexOf('?') > -1 && currentURL.indexOf(list) < 0 ){
        console.log("-->1");
        window.history.replaceState(null, null, currentURL +'&region[]='+list);
    }
    else if(currentURL.indexOf('?') < 0 && currentURL.indexOf(list) < 0 ) {
        console.log("-->2");
        window.history.replaceState(null, null, currentURL + "?type=rent&sort=senaste&" + 'region[]='+list);
    }else if(currentURL.indexOf('?') > -1 && currentURL.indexOf(list) > -1){
        console.log("-->3");
        //this.removeurlparameter(currentURL, list);
        cstmremoveurlparameter(currentURL, list);
    }
}

function cstmremoveurlparameter(url, param)
{
    var urlparts = url.split('?');   
    if (urlparts.length >= 2) {
        var content = decodeURIComponent('region[]');
        console.log(content);
        console.log(urlparts);
        var prefix = content+'='+param;
        var pars = urlparts[1].split(/[&;]/g);
        
        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        // return url;
        window.history.replaceState(null, null, url);
        // console.log(url);
    } 
}