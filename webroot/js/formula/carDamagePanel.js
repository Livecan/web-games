var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { Sprite } from './sprite.js';
import { carSprites } from './variables.js';
import { DamagePanel } from './damagePanel.js';
import { nvl } from '../module/missingJSXFunctions.js';

export var CarDamagePanel = function (_React$Component) {
  _inherits(CarDamagePanel, _React$Component);

  function CarDamagePanel() {
    _classCallCheck(this, CarDamagePanel);

    return _possibleConstructorReturn(this, (CarDamagePanel.__proto__ || Object.getPrototypeOf(CarDamagePanel)).apply(this, arguments));
  }

  _createClass(CarDamagePanel, [{
    key: 'compare',
    value: function compare(a, b) {
      var lastOrder = 999;
      if (a.state == b.state) {
        if (a.state == "R" && nvl(a.order, lastOrder) < nvl(b.order, lastOrder)) {
          //both racing - compare order
          return -1;
        }
        if (a.state == "F" && a.ranking < b.ranking) {
          //both finished - compare ranking
          return -1;
        }
        return 1;
      }
      if (a.state == "R") {
        //only A is racing
        return -1;
      }
      if (b.state == "R") {
        //only B is racing
        return 1;
      }
      if (a.state == "F") {
        //A is racing and B retired
        return -1;
      }
      if (b.state == "F") {
        //B is racing and A retired
        return -1;
      }
      return 1;
    }
  }, {
    key: 'render',
    value: function render() {
      var _this2 = this;

      return React.createElement(
        'table',
        { id: 'car_stats_table', className: 'damage_table' },
        React.createElement(
          'tbody',
          null,
          this.props.cars.sort(this.compare).map(function (car) {
            return React.createElement(
              'tr',
              { key: car.index },
              React.createElement(
                'td',
                null,
                React.createElement(Sprite, { src: "img/formula/cars/" + carSprites[car.index],
                  className: 'car_img', key: car.id,
                  width: '20', height: '50', unit: 'px' })
              ),
              React.createElement(
                'td',
                null,
                {
                  'R': //racing - display current gear
                  React.createElement(Sprite, { src: "img/formula/gears/" + Math.max(1, car.gear || 1) + ".svg",
                    className: 'state',
                    width: '20px', height: '20px' }),
                  'F': //finished - display ranking
                  React.createElement(
                    'span',
                    { className: 'ranking' },
                    React.createElement(Sprite, { src: 'img/formula/gears/finish.svg',
                      className: 'finish_img',
                      width: '20px', height: '20px'
                    }),
                    React.createElement(
                      'span',
                      null,
                      car.ranking
                    )
                  ),
                  'X': //retired - display X icon
                  React.createElement(Sprite, { src: 'img/formula/gears/out.svg',
                    className: 'state',
                    width: '20px', height: '20px' })
                }[car.state]
              ),
              React.createElement(
                'td',
                null,
                _this2.props.users.find(function (user) {
                  return user.id == car.user_id;
                }).name
              ),
              React.createElement(DamagePanel, { damages: car.fo_damages })
            );
          })
        )
      );
    }
  }]);

  return CarDamagePanel;
}(React.Component);