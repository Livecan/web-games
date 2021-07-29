var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

export var ZoomPanel = function (_React$Component) {
  _inherits(ZoomPanel, _React$Component);

  function ZoomPanel(props) {
    _classCallCheck(this, ZoomPanel);

    return _possibleConstructorReturn(this, (ZoomPanel.__proto__ || Object.getPrototypeOf(ZoomPanel)).call(this, props));
  }

  _createClass(ZoomPanel, [{
    key: "render",
    value: function render() {
      return React.createElement(
        React.Fragment,
        null,
        React.createElement(
          "button",
          { onClick: this.props.onRefresh },
          React.createElement("img", { src: "img/formula/refresh.svg", width: "30px", height: "30px" })
        ),
        this.props.noZoomIn ? null : React.createElement(
          "button",
          { onClick: this.props.onZoomIn },
          React.createElement("img", { src: "img/formula/plus.svg", width: "30px", height: "30px" })
        ),
        this.props.noZoomOut ? null : React.createElement(
          "button",
          { onClick: this.props.onZoomOut },
          React.createElement("img", { src: "img/formula/minus.svg", width: "30px", height: "30px" })
        )
      );
    }
  }]);

  return ZoomPanel;
}(React.Component);