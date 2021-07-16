var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { damageType } from './variables.js';

export var PitStopPanel = function (_React$Component) {
    _inherits(PitStopPanel, _React$Component);

    function PitStopPanel(props) {
        _classCallCheck(this, PitStopPanel);

        var _this = _possibleConstructorReturn(this, (PitStopPanel.__proto__ || Object.getPrototypeOf(PitStopPanel)).call(this, props));

        _this.state = { assignedPoints: {} };
        _this.addPoint = _this.addPoint.bind(_this);
        _this.removePoint = _this.removePoint.bind(_this);
        return _this;
    }

    _createClass(PitStopPanel, [{
        key: "addPoint",
        value: function addPoint(damageType) {
            assignedPoints = this.state.assignedPoints;
            console.log(damageType);
            console.log(JSON.stringify(this.props.car.fo_damages.find(function (damage) {
                return damage.type == damageType;
            })));
            assignedPoints[damageType] = Math.min(this.props.maxPoints.find(function (maxPoint) {
                return maxPoint.damage_type == damageType;
            }).max_points - this.props.car.fo_damages.find(function (damage) {
                return damage.type == damageType;
            }).wear_points, (assignedPoints[damageType] || 0) + 1);
            this.setState({ assignedPoints: assignedPoints });
        }
    }, {
        key: "removePoint",
        value: function removePoint(damageType) {
            assignedPoints = this.state.assignedPoints;
            assignedPoints[damageType] = Math.max(0, (assignedPoints[damageType] || 0) - 1);
            this.setState({ assignedPoints: assignedPoints });
        }
    }, {
        key: "render",
        value: function render() {
            var _this2 = this;

            console.log(JSON.stringify(this.props.availablePoints));
            console.log(JSON.stringify(this.props.maxPoints));
            console.log(JSON.stringify(this.props.car));
            return React.createElement(
                "table",
                { className: "damage_table" },
                React.createElement(
                    "tbody",
                    null,
                    this.props.car.fo_damages.map(function (damage) {
                        return React.createElement(
                            "tr",
                            { key: damage.type,
                                className: "damage " + damageTypeClass[damage.type - 1] },
                            React.createElement(
                                "td",
                                null,
                                React.createElement(
                                    "button",
                                    { onClick: function onClick() {
                                            return _this2.removePoint(damage.type);
                                        } },
                                    "-"
                                )
                            ),
                            React.createElement(
                                "td",
                                null,
                                damage.wear_points + (_this2.state.assignedPoints[damage.type] > 0 && " + " + _this2.state.assignedPoints[damage.type])
                            ),
                            React.createElement(
                                "td",
                                null,
                                React.createElement(
                                    "button",
                                    { onClick: function onClick() {
                                            return _this2.addPoint(damage.type);
                                        } },
                                    "+"
                                )
                            )
                        );
                    })
                )
            );
        }
    }]);

    return PitStopPanel;
}(React.Component);