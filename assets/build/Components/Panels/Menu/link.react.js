/**
 * A link menu item, properties:
 *
 * - name
 * - href
 * - icon
 *
 * @jsx React.DOM
 */

define([], function() {
    return React.createClass({
        handleClick: function() {
            flux.actions.click(this.props.item.getTarget(), this.props.panel, this.props.item);
        },
        render: function() {
            
            var className = 'menu-item-link auja-border-secondary ';
            
            //Create the icon class
            var icon = "fallback";
            if(this.props.item.getIcon()) {
                icon = this.props.item.getIcon();    
            }
            className += "icon ion-" + icon;
            
            //Check if we match the active item
            if(this.props.item.isActive()) {
                className += " auja-color-main";
            }
            
            return (
                React.DOM.li({className: className, onClick: this.handleClick}, 
                    React.DOM.span(null, this.props.item.getText())
                )
                );
        }
    });

});