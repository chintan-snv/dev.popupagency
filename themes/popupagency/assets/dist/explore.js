//# sourceMappingURL=explore.js.map


! function(e) {
    "function" == typeof define && define.amd ? define("explore", e) : e()
}(function() {
    "use strict";
    function c(e) {
        return (c = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
            return typeof e
        } : function(e) {
            return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        })(e)
    }(MyListing.Explore_Init = function() {
        document.getElementById("c27-explore-listings") && (MyListing.Explore = new Vue({
            el: "#c27-explore-listings",
            data: {
                activeType: !1,
                types: CASE27_Explore_Settings.ListingTypes,
                loading: !1,
                last_request: null,
                state: {
                    mobileTab: CASE27_Explore_Settings.ActiveMobileTab
                },
                mobile: window.matchMedia("screen and (max-width: 1200px)"),
                map: !1,
                baseUrl: CASE27_Explore_Settings.ExplorePage ? CASE27_Explore_Settings.ExplorePage : window.location.href.replace(window.location.search, "")
            },
            beforeMount: function() {
                this.setType(CASE27_Explore_Settings.ActiveListingType), this.mobile.matches && Object.keys(this.types).forEach(function(e) {
                    var t = this.types[e],
                        i = t.taxonomies[t.tab];
                    i && i.activeTermId || (t.tab = "search-form")
                }.bind(this)), this.jQueryReady()
            },
            methods: {
                setType: function(e) {
                    this.types[e] && (this.activeType = this.types[e])
                },
                getListings: MyListing.Helpers.debounce(function(e) {
                    window.getListings = true;
                    this.mobile.matches || this._getListings(e)
                }, 500),
                getListingsShort: MyListing.Helpers.debounce(function(e) {
                    this.mobile.matches || this._getListings(e)
                }, 250),
                _getListings: function(t) { 
                    "dev" === CASE27.env && console.log("%c Get Listings [" + t + "]", "background-color: darkred; color: #fff;"), this.loading = !0;
                    var i = this,
                        a = this.activeType;
                    this.activeType.filters.preserve_page || (this.activeType.filters.page = 0);
                    var e = this.activeType.taxonomies[this.activeType.tab];
                    if (void 0 !== e && 0 !== e.activeTermId) var s = {
                        context: "term-search",
                        taxonomy: e.tax,
                        term: e.activeTermId,
                        page: e.page,
                        sort: this.activeType.filters.sort,
                        search_location: this.activeType.filters.search_location,
                        search_location_lat: this.activeType.filters.search_location_lat,
                        search_location_lng: this.activeType.filters.search_location_lng,
                        proximity: this.activeType.filters.proximity,
                        proximity_units: this.activeType.filters.proximity_units
                    };
                    else s = this.activeType.filters;
                    var r = {
                            form_data: s,
                            listing_type: this.activeType.slug,
                            listing_wrap: CASE27_Explore_Settings.ListingWrap
                        },
                        n = JSON.stringify(r);
                    if (this.activeType.last_response && n === this.activeType.last_request_body) {
                        "dev" === CASE27.env && console.warn("Ignoring call to getListings, no search arguments have changed.");
                        var o = this.activeType.last_response;
                        return this.updateUrl(), void setTimeout(function() {
                            this.loading = !1, this.activeType.last_response && this.updateView(o, t)
                        }.bind(this), 200)
                    }
                    "dev" === CASE27.env && this.activeType.last_request_body && (console.log("%c Getting listings, arguments diff:", "color: #a370ff"), console.table(objectDiff(JSON.parse(this.activeType.last_request_body).form_data, JSON.parse(n).form_data))), this.updateUrl(), jQuery.ajax({
                        url: CASE27.mylisting_ajax_url + "&action=get_listings&security=" + CASE27.ajax_nonce,
                        type: "GET",
                        dataType: "json",
                        data: r,
                        beforeSend: function(e, t) {
                            i.last_request && i.last_request.abort(), i.last_request = e
                        },
                        success: function(e) {
                            "object" === c(e) && e.html && ("search-form" === a.tab && (a.last_response = e, a.last_request_body = n), a.slug === i.activeType.slug && (i.loading = !1, i.updateView(e, t)))
                        }
                    })
                },
                getNearbyListings: function() {
                    var e = this;
                    if (this.activeType.filters.search_location && this.activeType.filters.search_location.length && this.activeType.filters.search_location_lat && this.activeType.filters.search_location_lng) this._getListings("nearby-listings");
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
                        !1 === e._triggerLocationChange() && e._getListings("geolocation-denied"), t.refresh({
                            message: CASE27.l10n.nearby_listings_location_required,
                            timeout: 4e3,
                            dismissable: !0,
                            spinner: !1
                        }), jQuery(".search-filters.type-id-" + e.activeType.id + ' input[name="search_location"]').focus().one("input", function() {
                            t.hide()
                        })
                    }
                },
                updateUrl: function() {
                    if (!window.history || CASE27_Explore_Settings.DisableLiveUrlUpdate) return !1;
                    var s = this.activeType.filters,
                        r = {};
                    if (!window.location.search && CASE27_Explore_Settings.IsFirstLoad) return !1;
                    if (0 !== this.activeType.index && (r.type = this.activeType.slug), this.activeType.tab !== this.activeType.defaultTab && (r.tab = this.activeType.tab), "search-form" === this.activeType.tab && Object.keys(s).forEach(function(e) {
                            var t = s[e];
                            if (CASE27_Explore_Settings.FieldAliases[e]) var i = CASE27_Explore_Settings.FieldAliases[e];
                            else i = e;
                            if ("proximity_units" == e) return !1;
                            if (("search_location_lat" == e || "search_location_lng" == e) && t && void 0 !== s.search_location && s.search_location.length) {
                                i = "search_location_lat" == e ? "lat" : "lng";
                                var a = -1 < t.toString().indexOf("-") ? 9 : 8;
                                t = t.toString().substr(0, a)
                            }
                            return !!("proximity" != e || s.search_location_lat && s.search_location_lng) && (("_default" != e.substr(-8) || void 0 === s[e.substr(0, e.lastIndexOf("_default"))]) && ((!s[e + "_default"] || t != s[e + "_default"]) && ("page" === e && 0 < t && (t += 1, i = "pg"), void(t && void 0 !== t.length && t.length ? r[i] = t : "number" == typeof t && t && (r[i] = t)))))
                        }), this.currentTax && this.currentTax.activeTerm) window.history.replaceState(null, null, this.currentTax.activeTerm.link);
                    else {
                        var e = jQuery.param(r);
                        window.history.replaceState(null, null, this.baseUrl + (e.trim().length ? "?" + e : ""))
                    }
                },
                updateView: function(e, t) { 
                    var i = e;
                     
                        // Listen to owl events:
                        //jQuery('.owl-carousel').trigger('destroy.owl.carousel');
                        jQuery(".lf-background-carousel").owlCarousel('destroy');
                     this.backup = jQuery(".finder-listings .results-view").html();
                     if(window.getListings)
                     {
                        window.getListings = false;
                        alert("");
                        this.backup = "";
                     }
                      if(!jQuery('.job-manager-pagination').find('.current').parent("li").next("li").length)
                      {
                      	this.backup = "";
                      } 
                      if(  window.pagex ){ 
                      	 window.pagex = 0;
                      	this.backup = "";
                      	console.log("page refresh data empty",window.pagex);
                      } 
                    CASE27_Explore_Settings.IsFirstLoad = !1, this.activeType.filters.preserve_page = !1, jQuery(".finder-listings .results-view").length && jQuery(".finder-listings .results-view").html(this.backup +i.html), jQuery(".fc-type-2-results").length && jQuery(".fc-type-2-results").html(i.html), setTimeout(function() {
                        void 0 !== jQuery(".results-view.grid").data("isotope") && jQuery(".results-view.grid").isotope("destroy");
                        var e = {
                            itemSelector: ".grid-item"
                        };
                        jQuery("body").hasClass("rtl") && (e.originLeft = !1), jQuery(".results-view.grid").isotope(e)
                    }, 10), jQuery(".lf-background-carousel").owlCarousel({
                        margin: 20,
                        items: 1,
                        loop: !0
                    }), jQuery('[data-toggle="tooltip"]').tooltip({
                        trigger: "hover"
                    }), jQuery(".c27-explore-pagination").length && jQuery(".c27-explore-pagination").html(i.pagination), jQuery(".fl-results-no span").length && jQuery(".fl-results-no span").html(jQuery(".finder-listings .results-view .grid-item").length +" of "+ i.showing), jQuery(".finder-container .fc-one-column").length && CASE27_Explore_Settings.ScrollToResults && jQuery(".finder-container .fc-one-column").animate({
                        scrollTop: jQuery(".finder-search").outerHeight()
                    }), window.matchMedia("(min-width: 1200px)").matches ? (jQuery(".finder-container .fc-default .finder-listings").length && jQuery(".finder-container .fc-default .finder-listings").animate({
                        scrollTop: 0
                    }), "pagination" == t && !CASE27_Explore_Settings.ScrollToResults && jQuery(".finder-container .fc-one-column").length && jQuery(".finder-container .fc-one-column").animate({
                        scrollTop: jQuery(".finder-search").outerHeight()
                    })) : "results" === this.state.mobileTab && this._resultsScrollTop(), this.updateMap()
                },
                _resultsScrollTop: function() {
                    jQuery("html, body").animate({
                        scrollTop: jQuery("#c27-explore-listings").offset().top - 100
                    }, "slow")
                },
                setupMap: function() {
                    var e = jQuery(this.$el).find(".finder-map .map").attr("id");
                    if (!MyListing.Maps.getInstance(e)) return !1;
                    this.map = MyListing.Maps.getInstance(e).instance;
                    var t = this.map;
                    if (MyListing.Geocoder.setMap(t), navigator.geolocation) {
                        var i = !1,
                            a = !1,
                            s = document.getElementById("explore-map-location-ctrl");
                        s.addEventListener("click", function() {
                            navigator.geolocation.getCurrentPosition(function(e) {
                                a = a || new MyListing.Maps.Marker({
                                    position: new MyListing.Maps.LatLng(e.coords.latitude, e.coords.longitude),
                                    map: t,
                                    template: {
                                        type: "user-location"
                                    }
                                }), t.setZoom(CASE27_Explore_Settings.Map.default_zoom), t.setCenter(a.getPosition())
                            }, function(e) {
                                (i = i || new MyListing.Dialog({
                                    message: CASE27.l10n.geolocation_failed
                                })).visible || (i.refresh(), i.show())
                            })
                        }), this.map.addControl(s)
                    }
                },
                updateMap: function() {
                	var s = this;
                	console.log("map=============>",s);
                    if (s.map) {
                        s.map.$el.removeClass("mylisting-map-loading"), s.map.removeMarkers(), s.map.trigger("updating_markers");
                        var r = new MyListing.Maps.LatLngBounds;
                        jQuery(this.$el).find(".results-view .lf-item-container").each(function(e, t) {
                        	//console.log("1111");
                            var i = jQuery(t);
                            if (i.data("latitude") && i.data("longitude")) {
                                var a = new MyListing.Maps.Marker({
                                    position: new MyListing.Maps.LatLng(i.data("latitude"), i.data("longitude")),
                                    map: s.map,
                                    popup: new MyListing.Maps.Popup({
                                        content: '<div class="lf-item-container lf-type-2">' + i.html() + "</div>"
                                    }),
                                    template: {
                                        type: "advanced",
                                        thumbnail: i.data("thumbnail"),
                                        icon_name: i.data("category-icon"),
                                        icon_background_color: i.data("category-color"),
                                        icon_color: i.data("category-text-color"),
                                        listing_id: i.data("id")
                                    }
                                });
                                s.map.markers.push(a), r.extend(a.getPosition())
                            }
                        }), r.empty() || s.map.fitBounds(r), 17 < s.map.getZoom() && s.map.setZoom(17), (s.map.markers.length < 1 || r.empty()) && (s.map.setZoom(CASE27_Explore_Settings.Map.default_zoom), s.map.setCenter(new MyListing.Maps.LatLng(CASE27_Explore_Settings.Map.default_lat, CASE27_Explore_Settings.Map.default_lng))), s.map.trigger("updated_markers")
                    } else if (document.getElementsByClassName("finder-map").length) var e = setInterval(function() {
                        s.map && (clearInterval(e), s.updateMap())
                    }, 200)
                },
                getUserLocation: MyListing.Helpers.debounce(function(t, e) {
                    var i = this;
                    MyListing.Geocoder.setMap(this.map), MyListing.Geocoder.getUserLocation({
                        receivedCoordinates: function(e) {
                            i.activeType.filters.search_location_lat = e.coords.latitude, i.activeType.filters.search_location_lng = e.coords.longitude
                        },
                        receivedAddress: function(e) {
                            i.$set(i.activeType.filters, "search_location", e.address), setTimeout(function() {
                                i._triggerLocationChange()
                            }, 5), "function" == typeof t && t()
                        },
                        geolocationFailed: function() {
                            "function" == typeof e && e()
                        }
                    })
                }, 250),
                geocodeLocation: MyListing.Helpers.debounce(function(e) {
                    this._geocodeLocation(e)
                }, 300),
                _geocodeLocation: function(t) {
                    var i = this;
                    function a(e) {
                        i.activeType.filters.search_location_lat = e.latitude, i.activeType.filters.search_location_lng = e.longitude, i.activeType.filters.search_location = e.address, i._getListings("geocode-location")
                    }
                    return t.target.value.length ? t.detail.place && t.detail.place.address && t.detail.place.latitude && t.detail.place.longitude ? a(t.detail.place) : void jQuery(function() {
                        MyListing.Geocoder.geocode(t.target.value, function(e) {
                            if (e) return e.address = t.target.value, a(e)
                        })
                    }) : a({
                        latitude: !1,
                        longitude: !1,
                        address: ""
                    })
                },
                _triggerLocationChange: function(a) {
                    var e = jQuery(".search-filters.type-id-" + this.activeType.id + " .form-location-autocomplete");
                    return e.each(function(e, t) {
                        var i = document.createEvent("CustomEvent");
                        i.initCustomEvent("autocomplete:change", !1, !0, {
                            place: {
                                latitude: this.activeType.filters.search_location_lat,
                                longitude: this.activeType.filters.search_location_lng,
                                address: this.activeType.filters.search_location,
                                debounce: a
                            }
                        }), t.dispatchEvent(i), jQuery(t).trigger("change")
                    }.bind(this)), !!e.length
                },
                resetFilters: function(e) {
                    if (e && e.target) {
                        var t = jQuery(e.target).find("i");
                        t.removeClass("fa-spin"), setTimeout(function() {
                            t.addClass("fa-spin")
                        }, 5)
                    }
                    this.activeType.slug;
                    var s = this.activeType.filters;
                    jQuery(".search-filters.type-id-" + this.activeType.id + " .filter-wrapper > .form-group").each(function() {
                        var e = jQuery(this),
                            t = jQuery(this).data("key");
                        if (e.hasClass("wp-search-filter") && e.find('input[name="search_keywords"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("location-filter") && e.find('input[name="search_location"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("text-filter") && e.find('input[type="text"]').val("").trigger("input").get(0).dispatchEvent(new Event("input")), e.hasClass("checkboxes-filter") && (s[t] = Array.isArray(s[t]) ? [] : ""), e.hasClass("date-filter") && e.find(".datepicker-wrapper .reset-value").click(), e.hasClass("dateyear-filter") && e.find(".custom-select").val([]).trigger("change").trigger("select2:close"), e.hasClass("dropdown-filter") && e.find(".custom-select").val([]).trigger("change").trigger("select2:close"), e.hasClass("cts-term-hierarchy") && e.find(".term-select-0 select").val([]).trigger("change").trigger("select2:close"), e.hasClass("range-filter")) {
                            var i = e.find(".slider-range"),
                                a = i.slider("option");
                            "single" === e.data("type") ? i.slider("value", a.max) : i.slider("values", [a.min, a.max]), i.slider("option", "slide").apply(i, [null, {
                                value: a.max,
                                values: [a.min, a.max]
                            }])
                        }
                    })
                },
                jQueryReady: function() {
                    var a = this;
                    jQuery(function(i) {
                        i("body").on("click", ".c27-explore-pagination a", function(e) {
                            e.preventDefault();
                            var t = parseInt(i(this).data("page"), 10) - 1;
                            a.activeType.taxonomies[a.activeType.tab] && (a.activeType.taxonomies[a.activeType.tab].page = t), a.activeType.filters.page = t, a.activeType.filters.preserve_page = !0, a._getListings("pagination")
                        }), jQuery(".col-switch").click(function(e) {
                            a.map.trigger("resize")
                        }), jQuery("body").on("mouseenter", ".results-view .lf-item-container.listing-preview", function() {
                            jQuery(".marker-container .marker-icon." + jQuery(this).data("id")).addClass("active")
                        }), jQuery("body").on("mouseleave", ".results-view .lf-item-container.listing-preview", function() {
                            jQuery(".marker-container .marker-icon." + jQuery(this).data("id")).removeClass("active")
                        })
                    })
                },
                termsExplore: function(e, t, a) {
                    var s = this;
                    this.activeType.tab = e;
                    var r = this.activeType.taxonomies[this.activeType.tab],
                        i = (a = a || !1, this.activeType.tabs[this.activeType.tab] || {});
                    if (!r.termsLoading) {
                        "active" === t && (t = r.activeTerm), r.activeTerm = !1, a || (r.terms = !1), "object" === c(t) && t.term_id ? (r.activeTermId = t.term_id, r.activeTerm = t) : isNaN(parseInt(t, 10)) ? r.activeTermId = 0 : r.activeTermId = t, this.currentTax.page = 0, jQuery(".cts-explore-sort.cts-sort-type-id-" + this.activeType.id + " .toggle-rating .trigger-proximity-order.selected").length ? this.getNearbyListings() : this._getListings("terms-explore"), void 0 === CASE27_Explore_Settings.TermCache[this.activeType.slug] && (CASE27_Explore_Settings.TermCache[this.activeType.slug] = {}), void 0 === CASE27_Explore_Settings.TermCache[this.activeType.slug][r.tax] && (CASE27_Explore_Settings.TermCache[this.activeType.slug][r.tax] = {});
                        var n = CASE27_Explore_Settings.TermCache[this.activeType.slug][r.tax][r.activeTermId];
                        if (n) {
                            if ((!a || a && !n.hasMore) && void 0 !== n.pages[r.termsPage]) return r.activeTerm = n.details, r.hasMore = n.hasMore, r.terms = [], Object.keys(n.pages).forEach(function(t) {
                                Object.keys(n.pages[t]).forEach(function(e) {
                                    r.terms.push(n.pages[t][e])
                                })
                            }), void this.updateUrl();
                            r.termsPage = n.currentPage + 1
                        } else r.termsPage = 0;
                        r.termsLoading = !0, jQuery.ajax({
                            url: CASE27.mylisting_ajax_url + "&action=explore_terms&security=" + CASE27.ajax_nonce,
                            type: "GET",
                            dataType: "json",
                            data: {
                                taxonomy: r.tax,
                                parent_id: r.activeTermId,
                                type_id: this.activeType.id,
                                page: r.termsPage,
                                per_page: CASE27_Explore_Settings.TermSettings.count,
                                orderby: i.orderby,
                                order: i.order,
                                hide_empty: i.hide_empty ? "yes" : "no"
                            },
                            success: function(t) {
                                if (r.termsLoading = !1, 1 != t.success) return new MyListing.Dialog({
                                    message: t.message
                                });
                                var i = CASE27_Explore_Settings.TermCache[s.activeType.slug][r.tax];
                                i[r.activeTermId] || (i[r.activeTermId] = {
                                    details: {},
                                    pages: {}
                                }), r.activeTerm = t.details, r.hasMore = t.more, i[r.activeTermId].details = t.details, i[r.activeTermId].hasMore = t.more, i[r.activeTermId].currentPage = r.termsPage, i[r.activeTermId].pages[r.termsPage] = t.children, a ? Object.keys(t.children).forEach(function(e) {
                                    r.terms.push(t.children[e])
                                }) : (r.terms = [], Object.keys(i[r.activeTermId].pages).forEach(function(t) {
                                    Object.keys(i[r.activeTermId].pages[t]).forEach(function(e) {
                                        r.terms.push(i[r.activeTermId].pages[t][e])
                                    })
                                })), s.updateUrl()
                            }
                        })
                    }
                },
                termsGoBack: function(e) {
                    this.termsExplore(this.activeType.tab, e.parent), 0 !== parseInt(e.parent, 10) && (this.currentTax.page = 0, this.getListings("terms-go-back"))
                }
            },
            computed: {
                currentTax: function() {
                    return this.activeType.taxonomies[this.activeType.tab]
                },
                showBackToFilters: function() {
                    var e = this.activeType.tabs;
                    return e["search-form"] && (this.mobile.matches || 1 === Object.keys(e).length)
                }
            },
            watch: {
                activeType: function() {
                    if (this.activeType)
                        if ("search-form" === this.activeType.tab) {
                            var e = jQuery(".cts-explore-sort.cts-sort-type-id-" + this.activeType.id + " .toggle-rating .trigger-proximity-order.selected"),
                                t = jQuery(".search-filters.type-id-" + this.activeType.id + " .form-location-autocomplete");
                            this.activeType.last_response ? (this.loading = !1, this.updateUrl(), this.updateView(this.activeType.last_response, "switch-listing-type")) : e.length ? this.getNearbyListings() : t.length ? this._triggerLocationChange(!1) : this._getListings("switch-listing-type")
                        } else {
                            var i = parseInt(this.activeType.taxonomies[this.activeType.tab].activeTermId, 10);
                            isNaN(i) || 0 === i ? this.termsExplore(this.activeType.tab) : this.termsExplore(this.activeType.tab, i)
                        }
                }
            }
        }))
    })()
});
//# sourceMappingURL=explore.js.map
