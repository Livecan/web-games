var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { damageType } from './variables.js';
import { SetupPlayerReadyButton } from './setupPlayerReadyButton.js';

export var SetupPlayerCars = function (_React$Component) {
    _inherits(SetupPlayerCars, _React$Component);

    function SetupPlayerCars(props) {
        _classCallCheck(this, SetupPlayerCars);

        var _this = _possibleConstructorReturn(this, (SetupPlayerCars.__proto__ || Object.getPrototypeOf(SetupPlayerCars)).call(this, props));

        _this.handleChangeDamage = _this.handleChangeDamage.bind(_this);
        _this.handlePlayerReadyChange = _this.handlePlayerReadyChange.bind(_this);
        return _this;
    }

    _createClass(SetupPlayerCars, [{
        key: 'carDamageReduceSum',
        value: function carDamageReduceSum(accumulator, currentValue) {
            return accumulator + currentValue.wear_points;
        }
    }, {
        key: 'handleChangeDamage',
        value: function handleChangeDamage(event) {
            console.log(event.target.id + " " + event.target.value);
            if (this.props.readyState) {
                this.handlePlayerReadyChange(false);
            }
            this.props.onDamageChange(event.target.id, event.target.value);
        }
    }, {
        key: 'handlePlayerReadyChange',
        value: function handlePlayerReadyChange(ready) {
            this.props.onPlayerReadyChange(ready);
        }
    }, {
        key: 'carDamageConditionsCheck',
        value: function carDamageConditionsCheck(cars, totalWP) {
            var _this2 = this;

            return cars.every(function (car) {
                return car.fo_damages.reduce(_this2.carDamageReduceSum, 0) == _this2.props.totalWP;
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var _this3 = this;

            return React.createElement(
                React.Fragment,
                null,
                React.createElement(
                    'tr',
                    null,
                    React.createElement(
                        'td',
                        { colSpan: '6' },
                        React.createElement(
                            'span',
                            null,
                            this.props.editable ? "You" : this.props.name
                        ),
                        React.createElement(SetupPlayerReadyButton, {
                            disabled: !this.props.editable,
                            conditionsMet: this.carDamageConditionsCheck(this.props.cars, this.props.totalWP),
                            ready: this.props.readyState,
                            onClick: this.handlePlayerReadyChange
                        })
                    )
                ),
                this.props.cars.map(function (car) {
                    return React.createElement(
                        'tr',
                        { key: car.id },
                        car.fo_damages.map(function (damage) {
                            return React.createElement(
                                'td',
                                { key: damage.id, className: "damage " + damageType[damage.type - 1] },
                                React.createElement('input', Object.assign({ id: damage.id, type: 'number'
                                }, //TODO: refactoring here???
                                _this3.props.editable ? { defaultValue: damage.wear_points, onChange: _this3.handleChangeDamage } : { value: damage.wear_points, readOnly: true }))
                            );
                        })
                    );
                })
            );
        }
    }]);

    return SetupPlayerCars;
}(React.Component);