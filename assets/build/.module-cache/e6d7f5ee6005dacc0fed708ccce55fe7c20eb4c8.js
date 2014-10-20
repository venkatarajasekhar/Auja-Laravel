define(['build/Objects/Abstract/menu_item'], function (MenuItem) {

    var Resource = function (data) {

        //Call the parent constructor
        MenuItem.call(this, arguments);

        /**
         * Resource items
         * @type {Array}
         */
        this.items = [];

        /**
         * Our custom type
         * @type {string}
         */
        this.type = 'resource';

        /**
         * Target where to fetch the data from
         */
        this.target = data.target;

        /**
         * Paging options
         * @type {{}}
         */
        this.paging = {};

        /**
         * Getter for the id
         * @returns string
         * @override
         */
        this.getId = function () {
            return this.target;
        };

        /**
         * Getter for the target
         * @returns String
         */
        this.getTarget = function () {
            return this.target;
        };

        /**
         * Get items in this resource
         * @returns {Array}
         */
        this.getItems = function () {
            return this.items;
        };

        /**
         * Get the current paging
         * @returns Object
         */
        this.getPaging = function () {
            return this.paging;
        };

        /**
         * Update with new data
         * @param data
         */
        this.extend = function (data) {

            //Initialize like this otherwise we have circular dependencies
            var MenuItemFactory = require('build/Factories/menu_item_factory');
            
            //Create actual items
            data.items = data.items.map(function (item) {
                return MenuItemFactory.createItem(item);
            }.bind(this));

            this.items = this.items.concat(data.items);
            this.paging = data.paging;
        };

        /**
         * Set the active item in our childs
         * @param item
         */
        this.setActiveItem = function (item) {
            for (var i in this.items) {
                this.items[i].setIsActive(this.items[i].getId() == item.getId());
            }
        };

        /**
         * Update with new data
         * @param data
         */
        this.update = function (data) {
            if (data.items && (data.items.length > 0 || this.items.length == 0)) {

                //Initialize like this otherwise we have circular dependencies
                var MenuItemFactory = require('build/Factories/menu_item_factory');
                
                //When items are passed as data directly write them as the content
                //Since we receive a Resource object here it will always have items, so we check if it already
                //contains items, or if originally there were no items (this.items.length == 0)
                this.items = data.items.map(function (item) {
                    return MenuItemFactory.createItem(item);
                }.bind(this));
            } else if (!this.paging.total) {
                //When no total paging is defined reset and let the paging commence all over again
                this.items = [];
            } else {
                //When we have a clue what to call to update this resource do that
                //setTimeout is to handle the "async" behavior of dispatching
                setTimeout(function () {
                    flux.actions.updateResource(this, this.paging.total);
                }.bind(this), 1);
            }
        };
    };

    // Inherit Panel
    Resource.prototype = MenuItem;

    // Fix constructor
    Resource.prototype.constructor = Resource;

    return Resource;
});