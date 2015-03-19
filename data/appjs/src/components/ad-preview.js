"use strict";

var React = require('react');
var Router = require('react-router');
var Link = Router.Link;

var AdPreview = React.createClass({
    render() {
        var item = this.props.item;
        return (
            <Link to="detail" params={{ id: item.id }}>
                <div className="col-xs-12 col-sm-4 col-md-4">
                    <div className="thumbnail">

                        <div style={{ height: 162, overflow: "hidden" }}><img width="100%" alt={item.name} src={item.image_url} /></div>

                        <div className="caption">
                            <h4>{item.name}</h4>
                            <span className="text-muted">{item.district}</span>
                            <span className="pull-right"><strong>{item.price}</strong></span>
                        </div>
                    </div>
                </div>
            </Link>
        );
    }
});

module.exports = AdPreview;