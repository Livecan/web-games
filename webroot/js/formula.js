var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * Formula Game React App
 */

import { SlidePanel, SlidePanelStack } from './module/slidePanel.js';
import { damageType } from './formula/variables.js';
import { PitStopPanel } from './formula/pitStopPanel.js';
import { GearChoicePanel } from './formula/gearChoicePanel.js';

var Board = function (_React$Component) {
    _inherits(Board, _React$Component);

    function Board(props) {
        _classCallCheck(this, Board);

        var _this = _possibleConstructorReturn(this, (Board.__proto__ || Object.getPrototypeOf(Board)).call(this, props));

        _this.zooms = ["100%", "150%", "200%", "250%", "300%"];

        _this.state = { boardZoom: 0 };
        _this.update = _this.update.bind(_this);
        _this.updateGameData = _this.updateGameData.bind(_this);
        _this.update(); //TODO: run this after the document loaded
        _this.changeRefresh = _this.changeRefresh.bind(_this);
        _this.chooseGear = _this.chooseGear.bind(_this);
        _this.showDamageOptions = _this.showDamageOptions.bind(_this);
        _this.chooseMoveOption = _this.chooseMoveOption.bind(_this);
        return _this;
    }

    _createClass(Board, [{
        key: 'changeRefresh',
        value: function changeRefresh() {
            if (this.state.refresher != null) {
                clearInterval(this.state.refresher);
                this.setState({ refresher: null });
            } else {
                this.setState({ refresher: setInterval(this.update, 1000) });
            }
        }
    }, {
        key: 'updateBoardZoom',
        value: function updateBoardZoom(zoom) {
            if (zoom > 0) {
                this.state.boardZoom = Math.min(this.state.boardZoom + 1, this.zooms.length - 1);
            }
            if (zoom < 0) {
                this.state.boardZoom = Math.max(this.state.boardZoom - 1, 0);
            }
            this.setState(this.state);
        }
    }, {
        key: 'updateGameData',
        value: function updateGameData(data) {
            if (data.has_updated) {
                this.setState({
                    gameState: data.game_state_id,
                    trackDebris: data.fo_debris,
                    cars: data.fo_cars.map(function (car, index) {
                        car.index = index;
                        return car;
                    }),
                    users: data.users,
                    logs: data.fo_logs, //TODO: refactor/use it in a nice UI element
                    actions: /*{type: "choose_pits",
                             available_points: [
                             {
                                points: 6,
                                damage_types: [1, 2, 3, 4, 5, 6],
                             },
                             ],
                             max_points: [
                             {
                                damage_type: 1,
                                max_points: 8,
                             },
                             {
                                damage_type: 2,
                                max_points: 4,
                             },
                             {
                                damage_type: 3,
                                max_points: 3,
                             },
                             {
                                damage_type: 4,
                                max_points: 4,
                             },
                             {
                                damage_type: 5,
                                max_points: 3,
                             },
                             {
                                damage_type: 6,
                                max_points: 3,
                             },
                             ]
                             },*/
                    data.actions,
                    modified: data.modified
                });
            }
        }
    }, {
        key: 'chooseGear',
        value: function chooseGear(gear) {
            $.post('/formula/chooseGear/' + this.props.id, { _csrfToken: csrfToken, game_id: this.props.id, gear: gear }, this.update, "json");
            console.log("chooseGear(" + gear + ")");
        }
    }, {
        key: 'update',
        value: function update() {
            $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateGameData);
        }
    }, {
        key: 'showDamageOptions',
        value: function showDamageOptions(positionId) {
            this.setState({ selectedPosition: positionId });
        }
    }, {
        key: 'chooseMoveOption',
        value: function chooseMoveOption(moveOptionId) {
            this.setState({ selectedPosition: null });
            $.post('/formula/chooseMoveOption/' + this.props.id, { _csrfToken: csrfToken, game_id: this.props.id, move_option_id: moveOptionId }, this.update, "json");
            console.log("chooseMoveOption(" + moveOptionId + ")");
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            return React.createElement(
                'div',
                { id: 'board_parent' },
                React.createElement(
                    'div',
                    { className: 'overflow_helper' },
                    React.createElement(
                        'div',
                        { id: 'board', style: { width: this.zooms[this.state.boardZoom] } },
                        React.createElement(TrackImage, { src: this.props.gameBoard }),
                        console.log(JSON.stringify(this.state.actions)),
                        this.state.actions != undefined && this.state.actions.type == "choose_move" && React.createElement(AvailableMovesSelectorOverlay, {
                            availableMoves: this.state.actions.available_moves,
                            positions: this.props.positions,
                            onMovePositionSelected: this.showDamageOptions }),
                        React.createElement(TrackCars, { cars: (this.state.cars || []).filter(function (car) {
                                return car.fo_position_id != null;
                            }),
                            positions: this.props.positions }),
                        React.createElement(TrackDebris, { debris: this.state.trackDebris || [], positions: this.props.positions })
                    )
                ),
                React.createElement(
                    SlidePanelStack,
                    { className: 'slide_panel_stack_top' },
                    React.createElement(
                        SlidePanel,
                        { showIcon: '/img/formula/downarrow.svg',
                            hideIcon: '/img/formula/uparrow.svg' },
                        React.createElement(ZoomPanel, { onRefresh: this.update,
                            noZoomIn: this.state.boardZoom == this.zooms.length - 1,
                            noZoomOut: this.state.boardZoom == 0,
                            onZoomOut: this.updateBoardZoom.bind(this, -1),
                            onZoomIn: this.updateBoardZoom.bind(this, 1)
                        })
                    ),
                    React.createElement(
                        SlidePanel,
                        { showIcon: '/img/formula/downarrow.svg',
                            hideIcon: '/img/formula/uparrow.svg' },
                        React.createElement(RefreshPanel, { paused: this.state.refresher == null,
                            onPlayPause: this.changeRefresh })
                    )
                ),
                React.createElement(
                    SlidePanelStack,
                    { className: 'slide_panel_stack_bottom' },
                    this.state.actions != undefined && this.state.actions.type == "choose_gear" && React.createElement(
                        SlidePanel,
                        { showIcon: '/img/formula/uparrow.svg',
                            hideIcon: '/img/formula/downarrow.svg' },
                        React.createElement(GearChoicePanel, { current: this.state.actions.current_gear,
                            available: this.state.actions.available_gears,
                            onChooseGear: this.chooseGear })
                    ),
                    this.state.selectedPosition != null && React.createElement(
                        SlidePanel,
                        { showIcon: '/img/formula/uparrow.svg',
                            hideIcon: '/img/formula/downarrow.svg' },
                        React.createElement(MoveDamageSelector, { positionId: this.state.selectedPosition,
                            onSelected: this.chooseMoveOption,
                            moveOptions: this.state.actions.available_moves.filter(function (move) {
                                return move.fo_position_id == _this2.state.selectedPosition;
                            }) })
                    ),
                    this.state.actions != undefined && this.state.actions.type == "choose_pits" && React.createElement(
                        SlidePanel,
                        { showIcon: '/img/formula/uparrow.svg',
                            hideIcon: '/img/formula/downarrow.svg' },
                        React.createElement(PitStopPanel, { car: this.state.cars.filter(function (car) {
                                return car.state == "R";
                            }).sort(function (car) {
                                return car.order;
                            })[0],
                            availablePoints: this.state.actions.available_points,
                            maxPoints: this.state.actions.max_points })
                    ),
                    React.createElement(
                        SlidePanel,
                        { showText: 'cars stats',
                            hideIcon: '/img/formula/downarrow.svg' },
                        React.createElement(CarDamagePanel, { update: Math.random(),
                            cars: this.state.cars || [], users: this.state.users })
                    )
                )
            );
        }
    }]);

    return Board;
}(React.Component);

var MoveDamageSelector = function (_React$Component2) {
    _inherits(MoveDamageSelector, _React$Component2);

    function MoveDamageSelector() {
        _classCallCheck(this, MoveDamageSelector);

        return _possibleConstructorReturn(this, (MoveDamageSelector.__proto__ || Object.getPrototypeOf(MoveDamageSelector)).apply(this, arguments));
    }

    _createClass(MoveDamageSelector, [{
        key: 'render',
        value: function render() {
            var _this4 = this;

            console.log(JSON.stringify(this.props.moveOptions)); //TODO: render the damage table; as props receive ONLY RELEVANT damages with corresponding MoveOptionId
            return React.createElement(
                'table',
                { id: "damage_table_" + this.props.positionId,
                    className: 'move_option_damage damage_table' },
                React.createElement(
                    'tbody',
                    null,
                    this.props.moveOptions.map(function (moveOption) {
                        return React.createElement(
                            'tr',
                            { key: moveOption.id,
                                onClick: function onClick() {
                                    return _this4.props.onSelected(moveOption.id);
                                } },
                            React.createElement(DamagePanel, { damages: moveOption.fo_damages })
                        );
                    })
                )
            );
        }
    }]);

    return MoveDamageSelector;
}(React.Component);

var AvailableMovesSelectorOverlay = function (_React$Component3) {
    _inherits(AvailableMovesSelectorOverlay, _React$Component3);

    function AvailableMovesSelectorOverlay() {
        _classCallCheck(this, AvailableMovesSelectorOverlay);

        return _possibleConstructorReturn(this, (AvailableMovesSelectorOverlay.__proto__ || Object.getPrototypeOf(AvailableMovesSelectorOverlay)).apply(this, arguments));
    }

    _createClass(AvailableMovesSelectorOverlay, [{
        key: 'render',
        value: function render() {
            var _this6 = this;

            var availableMovesPositionIds = Array.from(new Set(this.props.availableMoves.map(function (move) {
                return move.fo_position_id;
            })));
            return React.createElement(
                'svg',
                { id: 'formula_board', className: 'board__svg' },
                availableMovesPositionIds.map(function (positionId) {
                    return React.createElement('circle', { key: positionId,
                        id: "move_position_" + positionId, className: 'move_option',
                        cx: _this6.props.positions[positionId].x / 1000 + "%",
                        cy: _this6.props.positions[positionId].y / 1000 + "%",
                        r: '.8%', fill: 'purple',
                        onClick: function onClick() {
                            return _this6.props.onMovePositionSelected(positionId);
                        } });
                })
            );
        }
    }]);

    return AvailableMovesSelectorOverlay;
}(React.Component);

var DamagePanel = function (_React$Component4) {
    _inherits(DamagePanel, _React$Component4);

    function DamagePanel() {
        _classCallCheck(this, DamagePanel);

        return _possibleConstructorReturn(this, (DamagePanel.__proto__ || Object.getPrototypeOf(DamagePanel)).apply(this, arguments));
    }

    _createClass(DamagePanel, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                React.Fragment,
                null,
                this.props.damages.map(function (damage) {
                    return React.createElement(
                        'td',
                        { key: damage.type,
                            className: "damage " + damageType[damage.type - 1] },
                        damage.wear_points
                    );
                })
            );
        }
    }]);

    return DamagePanel;
}(React.Component);

var CarDamagePanel = function (_React$Component5) {
    _inherits(CarDamagePanel, _React$Component5);

    function CarDamagePanel() {
        _classCallCheck(this, CarDamagePanel);

        return _possibleConstructorReturn(this, (CarDamagePanel.__proto__ || Object.getPrototypeOf(CarDamagePanel)).apply(this, arguments));
    }

    _createClass(CarDamagePanel, [{
        key: 'order',
        value: function order(car) {
            var value = (car.state == "R" ? -1000 : 0) + (car.order || 100);
            return value;
        }
    }, {
        key: 'render',
        value: function render() {
            var _this9 = this;

            return React.createElement(
                'table',
                { id: 'car_stats_table', className: 'damage_table' },
                React.createElement(
                    'tbody',
                    null,
                    this.props.cars.sort(function (first, second) {
                        return _this9.order(first) - _this9.order(second) < 0 ? -1 : 0;
                    }).map(function (car) {
                        return React.createElement(
                            'tr',
                            { key: car.index },
                            React.createElement(
                                'td',
                                null,
                                React.createElement(Sprite, { src: "/img/formula/cars/" + carSprites[car.index],
                                    className: 'car_img', key: car.id,
                                    width: '20', height: '50', unit: 'px' })
                            ),
                            React.createElement(
                                'td',
                                null,
                                _this9.props.users.find(function (user) {
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

var RefreshPanel = function (_React$Component6) {
    _inherits(RefreshPanel, _React$Component6);

    function RefreshPanel() {
        _classCallCheck(this, RefreshPanel);

        return _possibleConstructorReturn(this, (RefreshPanel.__proto__ || Object.getPrototypeOf(RefreshPanel)).apply(this, arguments));
    }

    _createClass(RefreshPanel, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                React.Fragment,
                null,
                React.createElement(
                    'button',
                    { onClick: this.props.onPlayPause },
                    this.props.paused ? React.createElement('img', { src: '/img/formula/play.svg', width: '30px', height: '30px' }) : React.createElement('img', { src: '/img/formula/pause.svg', width: '30px', height: '30px' })
                )
            );
        }
    }]);

    return RefreshPanel;
}(React.Component);

var ZoomPanel = function (_React$Component7) {
    _inherits(ZoomPanel, _React$Component7);

    function ZoomPanel(props) {
        _classCallCheck(this, ZoomPanel);

        return _possibleConstructorReturn(this, (ZoomPanel.__proto__ || Object.getPrototypeOf(ZoomPanel)).call(this, props));
    }

    _createClass(ZoomPanel, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                React.Fragment,
                null,
                React.createElement(
                    'button',
                    { onClick: this.props.onRefresh },
                    React.createElement('img', { src: '/img/formula/refresh.svg', width: '30px', height: '30px' })
                ),
                this.props.noZoomIn ? null : React.createElement(
                    'button',
                    { onClick: this.props.onZoomIn },
                    React.createElement('img', { src: '/img/formula/plus.svg', width: '30px', height: '30px' })
                ),
                this.props.noZoomOut ? null : React.createElement(
                    'button',
                    { onClick: this.props.onZoomOut },
                    React.createElement('img', { src: '/img/formula/minus.svg', width: '30px', height: '30px' })
                )
            );
        }
    }]);

    return ZoomPanel;
}(React.Component);

var TrackImage = function (_React$Component8) {
    _inherits(TrackImage, _React$Component8);

    function TrackImage() {
        _classCallCheck(this, TrackImage);

        return _possibleConstructorReturn(this, (TrackImage.__proto__ || Object.getPrototypeOf(TrackImage)).apply(this, arguments));
    }

    _createClass(TrackImage, [{
        key: 'render',
        value: function render() {
            return React.createElement('img', { className: 'board__track', src: this.props.src });
        }
    }]);

    return TrackImage;
}(React.Component);

var carSprites = ["1a.png", "1b.png", "2a.png", "2b.png", "3a.png", "3b.png", "4a.png", "4b.png", "5a.png", "5b.png", "6a.png", "6b.png"];

var TrackCars = function (_React$Component9) {
    _inherits(TrackCars, _React$Component9);

    function TrackCars() {
        _classCallCheck(this, TrackCars);

        return _possibleConstructorReturn(this, (TrackCars.__proto__ || Object.getPrototypeOf(TrackCars)).apply(this, arguments));
    }

    _createClass(TrackCars, [{
        key: 'render',
        value: function render() {
            var _this14 = this;

            if (this.props.cars == null) {
                return null;
            } else {
                return React.createElement(
                    React.Fragment,
                    null,
                    this.props.cars.map(function (car) {
                        return React.createElement(Sprite, { src: "/img/formula/cars/" + carSprites[car.index],
                            className: 'car_img',
                            key: car.index,
                            x: _this14.props.positions[car.fo_position_id].x / 1000,
                            y: _this14.props.positions[car.fo_position_id].y / 1000,
                            angle: _this14.props.positions[car.fo_position_id].angle * 180 / Math.PI - 90
                        });
                    })
                );
            }
        }
    }]);

    return TrackCars;
}(React.Component);

var TrackDebris = function (_React$Component10) {
    _inherits(TrackDebris, _React$Component10);

    function TrackDebris() {
        _classCallCheck(this, TrackDebris);

        return _possibleConstructorReturn(this, (TrackDebris.__proto__ || Object.getPrototypeOf(TrackDebris)).apply(this, arguments));
    }

    _createClass(TrackDebris, [{
        key: 'render',
        value: function render() {
            var _this16 = this;

            if (this.props.debris == null) {
                return null;
            } else {
                return React.createElement(
                    React.Fragment,
                    null,
                    this.props.debris.map(function (item) {
                        return React.createElement(Sprite, { src: "/img/formula/track-objects/oil.png",
                            className: 'debris_img',
                            key: item.id,
                            x: _this16.props.positions[item.fo_position_id].x / 1000,
                            y: _this16.props.positions[item.fo_position_id].y / 1000,
                            angle: _this16.props.positions[item.fo_position_id].angle * 180 / Math.PI - 90
                        });
                    })
                );
            }
        }
    }]);

    return TrackDebris;
}(React.Component);

var Sprite = function (_React$Component11) {
    _inherits(Sprite, _React$Component11);

    function Sprite() {
        _classCallCheck(this, Sprite);

        return _possibleConstructorReturn(this, (Sprite.__proto__ || Object.getPrototypeOf(Sprite)).apply(this, arguments));
    }

    _createClass(Sprite, [{
        key: 'render',
        value: function render() {
            var width = this.props.width || .8;
            var height = this.props.height || 2;
            var unit = this.props.unit || "%";
            return React.createElement('img', { src: this.props.src,
                className: this.props.className,
                width: width + unit, height: height + unit,
                style: {
                    left: this.props.x - width / 2 + unit,
                    top: this.props.y - height / 2 + unit,
                    transform: "rotate(" + this.props.angle + "deg)",
                    transformOrigin: this.props.transformOrigin
                } });
        }
    }]);

    return Sprite;
}(React.Component);

ReactDOM.render(React.createElement(Board, { id: id, gameBoard: gameBoard, positions: positions }), document.getElementById('root'));