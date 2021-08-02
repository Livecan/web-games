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
            var _this2 = this;

            return React.createElement("circle", { className: "gear_select",
                cx: this.gearPositions[this.props.gear - 1].x,
                cy: this.gearPositions[this.props.gear - 1].y,
                r: "50", fillOpacity: "0",
                strokeWidth: this.state.hover ? "20" : "10",
                stroke: this.props.color,
                onMouseEnter: function onMouseEnter() {
                    _this2.handleMouseEnter();
                    _this2.props.onMouseEnter();
                },
                onMouseLeave: function onMouseLeave() {
                    _this2.handleMouseLeave();
                    _this2.props.onMouseLeave();
                },
                onClick: this.handleMouseClick });
        }
    }]);

    return GearSelector;
}(React.Component);

export var GearChoicePanel = function (_React$Component2) {
    _inherits(GearChoicePanel, _React$Component2);

    function GearChoicePanel(props) {
        _classCallCheck(this, GearChoicePanel);

        var _this3 = _possibleConstructorReturn(this, (GearChoicePanel.__proto__ || Object.getPrototypeOf(GearChoicePanel)).call(this, props));

        _this3.gearRolls = [[1, 2], [2, 4], [4, 8], [7, 12], [11, 20], [21, 30]];

        _this3.state = { selected: null };
        _this3.mouseMoveHandler = _this3.mouseMoveHandler.bind(_this3);
        return _this3;
    }

    _createClass(GearChoicePanel, [{
        key: "mouseMoveHandler",
        value: function mouseMoveHandler(evt) {
            console.log(evt);
            var tooltipId = "gearChoice";
            if (this.state.selected != null) {
                this.props.onDisplayTooltip(tooltipId, evt.nativeEvent.clientX + 10, evt.nativeEvent.clientY + 10, "Rolls " + this.gearRolls[this.state.selected - 1][0] + " - " + this.gearRolls[this.state.selected - 1][1] + ".");
            }
            if (this.state.selected == null) {
                this.props.onHideTooltip(tooltipId);
            }
        }
    }, {
        key: "render",
        value: function render() {
            var _this4 = this;

            return React.createElement(
                "div",
                { id: "gear_choice", style: { position: "relative" }, onMouseMove: this.mouseMoveHandler },
                React.createElement("img", { src: "img/formula/gearbox.svg", className: "gear_choice",
                    width: "100px", height: "100px" }),
                React.createElement(
                    "svg",
                    { viewBox: "0 0 600 600", width: "100", height: "100",
                        style: { position: "absolute", top: 0, left: 0 } },
                    this.props.available.map(function (gear) {
                        return React.createElement(GearSelector, { key: gear, gear: gear,
                            color: gear == _this4.props.current ? "green" : "red",
                            onClick: function onClick() {
                                return _this4.props.onChooseGear(gear);
                            },
                            onMouseEnter: function onMouseEnter() {
                                return _this4.setState({ selected: gear });
                            },
                            onMouseLeave: function onMouseLeave() {
                                return _this4.setState({ selected: null });
                            } });
                    })
                )
            );
        }
    }]);

    return GearChoicePanel;
}(React.Component);