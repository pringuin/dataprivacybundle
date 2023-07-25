pimcore.registerNS("pimcore.plugin.dataprivacybundle");

pimcore.plugin.dataprivacybundle = Class.create({
    initialize: function () {
        document.addEventListener(pimcore.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    openIndexPage: function () {
        try {
            pimcore.globalmanager.get('dataprivacyadmin_index').activate();
        } catch (e) {
            pimcore.globalmanager.add('dataprivacyadmin_index', new pimcore.tool.genericiframewindow('index', '/pringuin_dataprivacy', "pimcore_icon_keys", t("Dataprivacy")));
        }
    },

    preMenuBuild: function (e) {
        // the event contains the existing menu
        let menu = e.detail.menu;



        let items = [{
            text: t("Dataprivacy"),
            iconCls: "pimcore_icon_keys", // make sure your icon class exists
            priority: 1, // define the position where you menu should be shown. Core menu items will leave a gap of 10 custom menu items
            itemId: 'pimcore_dataprivacybundle_dataprivacy', // specify your custom itemId here
            handler: this.openIndexPage, // define a handler what should happen if you click on the menu item
        }];
        // the property name is used as id with the prefix pimcore_menu_ in the html markup e.g. pimcore_menu_dataprivacybundle
        menu.dataprivacybundle = {
            label: t('Dataprivacy'), // set your label here, will be shown as tooltip
            iconCls: 'pimcore_icon_keys', // set full icon name here
            priority: 62, // define the position where you menu should be shown. Core menu items will leave a gap of 10 custom main menu items
            items: items, //if your main menu has subitems please see Adding Custom Submenus To ExistingNavigation Items
            shadow: false,
            //handler: this.opendataprivacybundle, // defining a handler will override the standard "showSubMenu" functionality, use in combination with "noSubmenus"
            noSubmenus: false, // if there are no submenus set to true otherwise menu won't show up
            cls: "pimcore_navigation_flyout", // use pimcore_navigation_flyout if you have subitems
        };
    },
});

var dataprivacybundle = new pimcore.plugin.dataprivacybundle();