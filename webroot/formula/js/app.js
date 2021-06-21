var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * Formula Game React App
 */

var Board = function (_React$Component) {
    _inherits(Board, _React$Component);

    function Board(props) {
        _classCallCheck(this, Board);

        var _this = _possibleConstructorReturn(this, (Board.__proto__ || Object.getPrototypeOf(Board)).call(this, props));

        _this.zooms = ["100%", "150%", "200%", "250%", "300%"];

        _this.state = { boardZoom: 0 };
        _this.update = _this.update.bind(_this);
        _this.updateGameData = _this.updateGameData.bind(_this);
        _this.update(); //TODO: run this after the document loaded
        return _this;
    }

    _createClass(Board, [{
        key: "updateBoardZoom",
        value: function updateBoardZoom(zoom) {
            if (zoom > 0) {
                this.state.boardZoom = Math.min(this.state.boardZoom + 1, this.zooms.length - 1);
            }
            if (zoom < 0) {
                this.state.boardZoom = Math.max(this.state.boardZoom - 1, 0);
            }
            this.setState(this.state);
        }
    }, {
        key: "updateGameData",
        value: function updateGameData(data) {
            var _this2 = this;

            if (data.has_updated) {
                this.setState({
                    gameState: data.game_state_id,
                    trackDebris: data.fo_debris.map(function (debris, index) {
                        return React.createElement(TrackDebris, { key: index,
                            x: _this2.props.positions[debris["fo_position_id"]].x / 1000,
                            y: _this2.props.positions[debris["fo_position_id"]].y / 1000,
                            angle: _this2.props.positions[debris["fo_position_id"]].angle * 180 / Math.PI - 90 });
                    }),
                    trackCars: data.fo_cars.map(function (car, index) {
                        return React.createElement(TrackCar, { key: car.id,
                            img_index: index,
                            x: _this2.props.positions[car["fo_position_id"]].x / 1000,
                            y: _this2.props.positions[car["fo_position_id"]].y / 1000,
                            angle: _this2.props.positions[car["fo_position_id"]].angle * 180 / Math.PI - 90 });
                    }),
                    carStats: {},
                    logs: data.fo_logs, //TODO: refactor/use it in a nice UI element
                    actions: data.actions,
                    modified: data.modified
                });
            }
        }
    }, {
        key: "update",
        value: function update() {
            $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateGameData);
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { id: "board_parent" },
                React.createElement(
                    "div",
                    { className: "overflow_helper" },
                    React.createElement(
                        "div",
                        { id: "board", style: { width: this.zooms[this.state.boardZoom] } },
                        React.createElement(TrackImage, { src: this.props.gameBoard }),
                        React.createElement("svg", { id: "formula_board", className: "board__svg" }),
                        this.state.trackCars,
                        this.state.trackDebris
                    )
                ),
                React.createElement(
                    SlidePanelStack,
                    null,
                    React.createElement(
                        SlidePanel,
                        null,
                        React.createElement(ZoomPanel, { onRefresh: this.update,
                            onZoomIn: this.updateBoardZoom.bind(this, 1),
                            onZoomOut: this.updateBoardZoom.bind(this, -1) })
                    ),
                    React.createElement(
                        SlidePanel,
                        null,
                        React.createElement(
                            "button",
                            null,
                            "Pause"
                        )
                    )
                )
            );
        }
    }]);

    return Board;
}(React.Component);

var ZoomPanel = function (_React$Component2) {
    _inherits(ZoomPanel, _React$Component2);

    function ZoomPanel(props) {
        _classCallCheck(this, ZoomPanel);

        var _this3 = _possibleConstructorReturn(this, (ZoomPanel.__proto__ || Object.getPrototypeOf(ZoomPanel)).call(this, props));

        _this3.onZoomIn = props.onZoomIn || function (arg) {};
        _this3.onZoomOut = props.onZoomOut || function (arg) {};
        _this3.onRefresh = props.onRefresh || function (arg) {};
        return _this3;
    }

    _createClass(ZoomPanel, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "button",
                    { onClick: this.onRefresh },
                    "Refresh"
                ),
                React.createElement(
                    "button",
                    { onClick: this.onZoomIn },
                    "+"
                ),
                React.createElement(
                    "button",
                    { onClick: this.onZoomOut },
                    "-"
                )
            );
        }
    }]);

    return ZoomPanel;
}(React.Component);

var SlidePanelStack = function (_React$Component3) {
    _inherits(SlidePanelStack, _React$Component3);

    function SlidePanelStack() {
        _classCallCheck(this, SlidePanelStack);

        return _possibleConstructorReturn(this, (SlidePanelStack.__proto__ || Object.getPrototypeOf(SlidePanelStack)).apply(this, arguments));
    }

    _createClass(SlidePanelStack, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "slide_panel_stack" },
                this.props.children
            );
        }
    }]);

    return SlidePanelStack;
}(React.Component);

var SlidePanel = function (_React$Component4) {
    _inherits(SlidePanel, _React$Component4);

    function SlidePanel(props) {
        _classCallCheck(this, SlidePanel);

        var _this5 = _possibleConstructorReturn(this, (SlidePanel.__proto__ || Object.getPrototypeOf(SlidePanel)).call(this, props));

        _this5.state = { visible: true };
        _this5.toggleHide = _this5.toggleHide.bind(_this5);
        //this.onToggleHide = props.onToggleHide || (arg => {});
        return _this5;
    }

    _createClass(SlidePanel, [{
        key: "toggleHide",
        value: function toggleHide() {
            this.state.visible = !this.state.visible;
            //this.onToggleHide(this.state.visible);
            this.setState(this.state);
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "slide_panel" },
                React.createElement(
                    "div",
                    { className: "slide_panel__content" + (this.state.visible ? "" : " hidden") },
                    this.props.children
                ),
                React.createElement(
                    "div",
                    { className: "slide_panel__buttons" },
                    React.createElement(
                        "button",
                        { className: "slide_panel__button", onClick: this.toggleHide },
                        this.state.visible ? "Hide" : "Show"
                    ),
                    React.createElement(
                        "span",
                        null,
                        this.props.modified
                    )
                )
            );
        }
    }]);

    return SlidePanel;
}(React.Component);

var TrackImage = function (_React$Component5) {
    _inherits(TrackImage, _React$Component5);

    function TrackImage() {
        _classCallCheck(this, TrackImage);

        return _possibleConstructorReturn(this, (TrackImage.__proto__ || Object.getPrototypeOf(TrackImage)).apply(this, arguments));
    }

    _createClass(TrackImage, [{
        key: "render",
        value: function render() {
            return React.createElement("img", { className: "board__track", src: this.props.src });
        }
    }]);

    return TrackImage;
}(React.Component);

var carSprites = ["tdrc01_car01_b.png", "tdrc01_car01_e.png", "tdrc01_car01_f.png", "tdrc01_car03_a.png", "tdrc01_car03_c.png", "tdrc01_car03_d.png", "tdrc01_car04_a.png", "tdrc01_car04_d.png", "tdrc01_car04_f.png", "tdrc01_car07_b.png", "tdrc01_car07_d.png", "tdrc01_car07_f.png"];

var TrackCar = function (_React$Component6) {
    _inherits(TrackCar, _React$Component6);

    function TrackCar() {
        _classCallCheck(this, TrackCar);

        return _possibleConstructorReturn(this, (TrackCar.__proto__ || Object.getPrototypeOf(TrackCar)).apply(this, arguments));
    }

    _createClass(TrackCar, [{
        key: "render",
        value: function render() {
            return React.createElement(TrackItem, { src: "/img/formula/cars/" + carSprites[this.props.img_index],
                className: "car_img",
                x: this.props.x,
                y: this.props.y,
                angle: this.props.angle
            });
        }
    }]);

    return TrackCar;
}(React.Component);

var TrackDebris = function (_React$Component7) {
    _inherits(TrackDebris, _React$Component7);

    function TrackDebris() {
        _classCallCheck(this, TrackDebris);

        return _possibleConstructorReturn(this, (TrackDebris.__proto__ || Object.getPrototypeOf(TrackDebris)).apply(this, arguments));
    }

    _createClass(TrackDebris, [{
        key: "render",
        value: function render() {
            return React.createElement(TrackItem, { src: "/img/formula/track-objects/oil.png",
                className: "debris_img",
                x: this.props.x,
                y: this.props.y,
                angle: this.props.angle
            });
        }
    }]);

    return TrackDebris;
}(React.Component);

var TrackItem = function (_React$Component8) {
    _inherits(TrackItem, _React$Component8);

    function TrackItem() {
        _classCallCheck(this, TrackItem);

        return _possibleConstructorReturn(this, (TrackItem.__proto__ || Object.getPrototypeOf(TrackItem)).apply(this, arguments));
    }

    _createClass(TrackItem, [{
        key: "render",
        value: function render() {
            var width = .8;
            var height = 2;
            return React.createElement("img", { src: this.props.src,
                className: this.props.className,
                width: width + "%", height: height + "%",
                style: {
                    left: this.props.x - width / 2 + "%",
                    top: this.props.y - height / 2 + "%",
                    transform: "rotate(" + this.props.angle + "deg)",
                    transformOrigin: "50% 50%"
                } });
        }
    }]);

    return TrackItem;
}(React.Component);

ReactDOM.render(React.createElement(Board, { id: id, gameBoard: gameBoard, positions: positions }), document.getElementById('root'));
