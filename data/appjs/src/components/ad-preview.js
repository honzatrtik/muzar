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
            <Link to="detail" params={{ id: item.get('id') }}>
                <div className="col-xs-12 col-sm-4 col-md-4">
                    <div className="thumbnail">

                        <div style={style}>
                            {src && <img width="100%" alt={item.get('name')} src={src} />}
                        </div>

                        <div className="caption">
                            <h4>{item.get('name')}</h4>
                            <span className="text-muted">{item.getIn(['contact', 'region'])}</span>
                            <span className="pull-right"><strong>{item.get('price')}</strong></span>
                        </div>
                    </div>
                </div>
            </Link>
        );
    }
});

module.exports = AdPreview;
