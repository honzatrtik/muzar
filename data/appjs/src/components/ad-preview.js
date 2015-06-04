"use strict";

var React = require('react');
var Router = require('react-router');
var Link = Router.Link;

var AdPreview = React.createClass({
    render() {

        var item = this.props.item;
        const src = item.getIn(['image_urls', 0]);
        const style = {
            backgroundColor: item.get('background_color') || '#eee',
            height: 162,
            overflow: "hidden"
        };
        return (
            <Link className="item" to="detail" params={{ id: item.get('id') }}>

                <div className="item-image" style={style}>
                    {src && <img width="100%" alt={item.get('name')} src={src} />}
                </div>

                <div className="item-caption">
                    <h4 className="item-caption-heading">{item.get('name')}</h4>
                    <span className="item-caption-location text-muted">{item.getIn(['contact', 'region'])}</span>
                    <span className="item-caption-price pull-right"><strong>{item.get('price')}</strong></span>
                </div>
                
            </Link>
        );
    }
});

module.exports = AdPreview;
