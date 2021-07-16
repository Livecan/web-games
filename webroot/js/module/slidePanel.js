var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

export var SlidePanelStack = function (_React$Component) {
    _inherits(SlidePanelStack, _React$Component);

    function SlidePanelStack() {
        _classCallCheck(this, SlidePanelStack);

        return _possibleConstructorReturn(this, (SlidePanelStack.__proto__ || Object.getPrototypeOf(SlidePanelStack)).apply(this, arguments));
    }

    _createClass(SlidePanelStack, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "slide_panel_stack " + this.props.className },
                this.props.children
            );
        }
    }]);

    return SlidePanelStack;
}(React.Component);

export var SlidePanel = function (_React$Component2) {
    _inherits(SlidePanel, _React$Component2);

    function SlidePanel(props) {
        _classCallCheck(this, SlidePanel);

        var _this2 = _possibleConstructorReturn(this, (SlidePanel.__proto__ || Object.getPrototypeOf(SlidePanel)).call(this, props));

        _this2.state = { visible: true };
        _this2.toggleHide = _this2.toggleHide.bind(_this2);
        //this.onToggleHide = props.onToggleHide || (arg => {});
        return _this2;
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
                        this.state.visible ? this.props.hideIcon ? React.createElement("img", { src: this.props.hideIcon, width: "30", height: "12" }) : this.props.hideText || "Hide" : this.props.showIcon ? React.createElement("img", { src: this.props.showIcon, width: "30", height: "12" }) : this.props.showText || "Show"
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