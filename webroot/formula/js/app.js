var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var ImgSprite = function (_React$Component) {
  _inherits(ImgSprite, _React$Component);

  function ImgSprite() {
    _classCallCheck(this, ImgSprite);

    return _possibleConstructorReturn(this, (ImgSprite.__proto__ || Object.getPrototypeOf(ImgSprite)).apply(this, arguments));
  }

  _createClass(ImgSprite, [{
    key: "render",
    value: function render() {
      var positionStyle = {
        left: this.props.left,
        top: this.props.top
      };
      return React.createElement("img", { className: this.props.elementClass,
        src: this.props.src,
        style: positionStyle,
        width: this.props.width,
        length: this.props.length
      });
    }

    //TODO: getImgSprite is a candidate for removal

  }], [{
    key: "getImgSprite",
    value: function getImgSprite(root, elementClass, src, left, top, width, length) {
      return React.createElement(ImgSprite, { elementClass: elementClass,
        src: src,
        left: left,
        top: top,
        width: width,
        length: length
      });
    }
  }]);

  return ImgSprite;
}(React.Component);