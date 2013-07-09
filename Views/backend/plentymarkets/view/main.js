// {namespace name=backend/Plentymarkets/view}
// {block name=backend/Plentymarkets/view/Main}
Ext.define('Shopware.apps.Plentymarkets.view.Main', {
	extend: 'Enlight.app.Window',

	alias: 'widget.plentymarkets-view-main',

	layout: 'fit',
	width: 860,
	height: '90%',
	autoShow: true,

	stateful: true,
	stateId: 'plentymarkets-view-main',

	title: '{s name=main/window/title}plentymarkets{/s}',
	iconCls: 'plenty-p',

	initComponent: function()
	{
		var me = this;
		me.callParent(arguments);
	},

	createTabPanel: function()
	{
		var me = this;

		me.start = Ext.widget('plentymarkets-view-start', {
			settings: me.settings,
			main: me
		});
		me.start.on('activate', me.start.init);

		me.api = Ext.widget('plentymarkets-view-api', {
			settings: me.settings,
			main: me
		});
		me.api.on('activate', me.api.build);

		me.sf = Ext.widget('plentymarkets-view-settings', {
			settings: me.settings,
			main: me
		});
		me.sf.on('activate', me.sf.build);

		me.tabpanel = Ext.create('Ext.tab.Panel', {
			items: [me.start, me.api, me.sf, {
				xtype: 'plentymarkets-view-export',
			}, {
				xtype: 'plentymarkets-view-mapping-main'
			}, {
				xtype: 'plentymarkets-view-log-main'
			}]

		});

		me.add(me.tabpanel);
		me.setTabAvailability();
	},

	setTabAvailability: function()
	{
		var me = this;
		var items = me.tabpanel.getTabBar().items;
		var statusToSet = me.settings.get('ApiStatus') != 2;

		if (me.settings.get('MayDatex') == 1)
		{
			Ext.getCmp('MayDatexUser').enable();
		}
		else
		{
			Ext.getCmp('MayDatexUser').disable();
		}

		items.get(2).setDisabled(statusToSet);
		items.get(3).setDisabled(statusToSet);
		items.get(4).setDisabled(statusToSet);
	}

});
// {/block}
