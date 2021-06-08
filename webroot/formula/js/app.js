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

        _this.update = _this.update.bind(_this);
        _this.updateState = _this.updateState.bind(_this);
        _this.update();
        return _this;
    }

    _createClass(Board, [{
        key: "updateState",
        value: function updateState(dataX) {
            alert(Object.keys(this));
            alert(Object.keys(dataX));
        }
    }, {
        key: "update",
        value: function update() {
            $.getJSON('/formula/getBoardUpdateJson/' + this.props.gameId, this.updateState);
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
                    React.createElement(TrackImage, { src: "/img/formula/daytona.jpg" })
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
            return React.createElement("img", { src: this.props.src });
        }
    }]);

    return TrackImage;
}(React.Component);

ReactDOM.render(React.createElement(
    Board,
    { gameId: "153" },
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