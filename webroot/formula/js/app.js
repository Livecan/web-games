var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Board = function (_React$Component) {
    _inherits(Board, _React$Component);

    function Board(props) {
        _classCallCheck(this, Board);

        var _this = _possibleConstructorReturn(this, (Board.__proto__ || Object.getPrototypeOf(Board)).call(this, props));

        _this.state = {};
        _this.update = _this.update.bind(_this);
        _this.updateState = _this.updateState.bind(_this);
        _this.update(); //TODO: run this after document loaded
        return _this;
    }

    _createClass(Board, [{
        key: "updateState",
        value: function updateState(data) {
            var _this2 = this;

            if (data.has_updated) {
                this.setState(function (state, props) {
                    return {
                        game_state: data.game_state_id,
                        trackDebris: data.fo_debris.map(function (debris) {
                            return React.createElement(TrackDebris, { key: debris.id,
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
                        logs: data.fo_logs, //TODO: refactor/use it in a nice UI element
                        actions: data.actions,
                        modified: data.modified
                    };
                });
            }
            //alert(JSON.stringify(data.fo_cars[0].fo_position_id));
            //alert(JSON.stringify(this.props.positions[data.fo_cars[0].fo_position_id]));
            //alert(Object.keys(data.fo_cars));
        }
    }, {
        key: "update",
        value: function update() {
            $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateState);
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { id: "board_parent", style: { overflow: "auto" } },
                React.createElement(
                    "div",
                    { id: "board" },
                    React.createElement(TrackImage, { src: this.props.gameBoard }),
                    React.createElement("svg", { id: "formula_board", className: "board__svg" }),
                    this.state.trackCars,
                    this.state.trackDebris
                )
            );
        }
    }]);

    return Board;
}(React.Component);

var TrackImage = function (_React$Component2) {
    _inherits(TrackImage, _React$Component2);

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

var TrackCar = function (_React$Component3) {
    _inherits(TrackCar, _React$Component3);

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

var TrackDebris = function (_React$Component4) {
    _inherits(TrackDebris, _React$Component4);

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

var TrackItem = function (_React$Component5) {
    _inherits(TrackItem, _React$Component5);

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
                    left: this.props.x - width / 2 + "%" /*"69.3%"*/
                    , top: this.props.y - height / 2 + "%" /*"42.8%"*/
                    , transform: "rotate(" + this.props.angle /*89.9087*/ + "deg)",
                    transformOrigin: "50% 50%"
                } });
        }
    }]);

    return TrackItem;
}(React.Component);

ReactDOM.render(React.createElement(
    Board,
    { id: id, gameBoard: gameBoard, positions: positions },
    React.createElement(
        "i",
        null,
        "Children "
    ),
    "test2",
    React.createElement(
        "b",
        null,
        "!"
    )
), document.getElementById('root'));
