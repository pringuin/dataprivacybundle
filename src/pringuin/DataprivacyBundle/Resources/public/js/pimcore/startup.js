pimcore.registerNS("pimcore.plugin.pringuinDataprivacyBundle");

pimcore.plugin.pringuinDataprivacyBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.pringuinDataprivacyBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);

        this.navEl = Ext.get('pimcore_menu_search').insertSibling('<li id="pimcore_menu_dataprivacy" data-menu-tooltip="Datenschutz" class="pimcore_menu_item pimcore_menu_needs_children"><img src="/bundles/pimcoreadmin/img/flat-white-icons/keys.svg"></li>', 'after');
        this.menu = new Ext.menu.Menu({
            items: [{
                text: t("Dataprivacy"),
                iconCls: "pimcore_icon_keys",
                handler: this.openIndexPage
            }],
            cls: "pimcore_navigation_flyout"
        });
        pimcore.layout.toolbar.prototype.dataprivacyMenu = this.menu;
    },

    openIndexPage: function () {
            try {
                pimcore.globalmanager.get('dataprivacyadmin_index').activate();
            } catch (e) {
                pimcore.globalmanager.add('dataprivacyadmin_index', new pimcore.tool.genericiframewindow('index', '/pringuin_dataprivacy', "pimcore_icon_keys", 'Datenschutz'));
            }
    },

    pimcoreReady: function (params, broker) {
        // alert("pringuinDataprivacyBundle ready!");
        var toolbar = pimcore.globalmanager.get("layout_toolbar");
        this.navEl.on("mousedown", toolbar.showSubMenu.bind(toolbar.dataprivacyMenu));
        pimcore.plugin.broker.fireEvent("dataprivacyMenuReady", toolbar.dataprivacyMenu);
    }
});

var pringuinDataprivacyBundlePlugin = new pimcore.plugin.pringuinDataprivacyBundle();
