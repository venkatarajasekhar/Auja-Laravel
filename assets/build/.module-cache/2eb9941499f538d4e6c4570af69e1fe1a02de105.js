/**
 * A spacer menu item, properties:
 * 
 * - name
 *
 * @jsx React.DOM
 */

define([], function() {
    return React.createClass({
        render: function() {
            return (
                React.DOM.li({className: "menu-item-spacer auja-color-main auja-border-secondary"}, this.props.item.getText())
                );
        }
    });

});