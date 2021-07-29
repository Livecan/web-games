var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var GearSelector = function (_React$Component) {
    _inherits(GearSelector, _React$Component);

    function GearSelector(props) {
        _classCallCheck(this, GearSelector);

        var _this = _possibleConstructorReturn(this, (GearSelector.__proto__ || Object.getPrototypeOf(GearSelector)).call(this, props));

        _this.gearPositions = [{ x: 191, y: 144 }, { x: 191, y: 457 }, { x: 300, y: 144 }, { x: 300, y: 457 }, { x: 412, y: 144 }, { x: 412, y: 457 }];

        _this.handleMouseEnter = _this.handleMouseEnter.bind(_this);
        _this.handleMouseLeave = _this.handleMouseLeave.bind(_this);
        _this.handleMouseClick = _this.handleMouseClick.bind(_this);
        _this.state = {};
        return _this;
    }

    _createClass(GearSelector, [{
        key: "handleMouseEnter",
        value: function handleMouseEnter() {
            this.setState({ hover: true });
        }
    }, {
        key: "handleMouseLeave",
        value: function handleMouseLeave() {
            this.setState({ hover: false });
        }
    }, {
        key: "handleMouseClick",
        value: function handleMouseClick() {
            typeof this.props.onClick == "function" && this.props.onClick();
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement("circle", { className: "gear_select",
                cx: this.gearPositions[this.props.gear - 1].x,
                cy: this.gearPositions[this.props.gear - 1].y,
                r: "50", fillOpacity: "0",
                strokeWidth: this.state.hover ? "20" : "10",
                stroke: this.props.color,
                onMouseEnter: this.handleMouseEnter,
                onMouseLeave: this.handleMouseLeave,
                onClick: this.handleMouseClick });
        }
    }]);

    return GearSelector;
}(React.Component);

export var GearChoicePanel = function (_React$Component2) {
    _inherits(GearChoicePanel, _React$Component2);

    function GearChoicePanel() {
        _classCallCheck(this, GearChoicePanel);

        return _possibleConstructorReturn(this, (GearChoicePanel.__proto__ || Object.getPrototypeOf(GearChoicePanel)).apply(this, arguments));
    }

    _createClass(GearChoicePanel, [{
        key: "render",
        value: function render() {
            var _this3 = this;

            console.log(JSON.stringify(this.props.available.filter(function (gear) {
                return gear != _this3.props.current;
            })));
            return React.createElement(
                "div",
                { style: { position: "relative" } },
                React.createElement("img", { src: "img/formula/gearbox.svg", className: "gear_choice",
                    width: "100px", height: "100px" }),
                React.createElement(
                    "svg",
                    { viewBox: "0 0 600 600", width: "100", height: "100",
                        style: { position: "absolute", top: 0, left: 0 } },
                    this.props.available.map(function (gear) {
                        return React.createElement(GearSelector, { key: gear, gear: gear,
                            color: gear == _this3.props.current ? "green" : "red",
                            onClick: function onClick() {
                                return _this3.props.onChooseGear(gear);
                            } });
                    })
                )
            );
        }
    }]);

    return GearChoicePanel;
}(React.Component);