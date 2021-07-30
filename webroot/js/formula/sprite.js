var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

export var Sprite = function (_React$Component) {
    _inherits(Sprite, _React$Component);

    function Sprite(props) {
        _classCallCheck(this, Sprite);

        var _this = _possibleConstructorReturn(this, (Sprite.__proto__ || Object.getPrototypeOf(Sprite)).call(this, props));

        _this.dimensionRegex = /(?<amount>\d*(?:\.\d*|))(?<unit>[^\d].*|)/;

        _this.state = { opacity: undefined };
        _this.setOpacity = _this.setOpacity.bind(_this);
        return _this;
    }

    _createClass(Sprite, [{
        key: "setOpacity",
        value: function setOpacity(opacity) {
            this.setState({ opacity: opacity });
        }
    }, {
        key: "render",
        value: function render() {
            var _this2 = this;

            var widthX = this.props.width.match(this.dimensionRegex);
            var heightX = this.props.height.match(this.dimensionRegex);

            return React.createElement("img", { src: this.props.src,
                className: this.props.className,
                width: this.props.width, height: this.props.height,
                style: {
                    left: this.props.x - widthX.groups["amount"] / 2 + widthX.groups["unit"],
                    top: this.props.y - heightX.groups["amount"] / 2 + heightX.groups["unit"],
                    transform: "rotate(" + this.props.angle + "deg)",
                    transformOrigin: this.props.transformOrigin,
                    opacity: this.state.opacity
                },
                onMouseEnter: this.props.disappearOnMouseOver ? function () {
                    return _this2.setOpacity(0);
                } : null,
                onMouseLeave: this.props.disappearOnMouseOver ? function () {
                    return _this2.setOpacity(undefined);
                } : null
            });
        }
    }]);

    return Sprite;
}(React.Component);