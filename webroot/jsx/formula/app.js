var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * Formula Game React App
 */

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
        return _this;
    }

    _createClass(Board, [{
        key: "changeRefresh",
        value: function changeRefresh() {
            if (this.state.refresher != null) {
                clearInterval(this.state.refresher);
                this.setState({ refresher: null });
            } else {
                this.setState({ refresher: setInterval(this.update, 1000) });
            }
        }
    }, {
        key: "updateBoardZoom",
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
        key: "updateGameData",
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
                    actions: data.actions,
                    modified: data.modified
                });
            }
        }
    }, {
        key: "chooseGear",
        value: function chooseGear(gear) {
            $.post('/formula/chooseGear/' + this.props.id, { _csrfToken: csrfToken, game_id: this.props.id, gear: gear }, this.update, "json");
            console.log("chooseGear(" + gear + ")");
        }
    }, {
        key: "update",
        value: function update() {
            $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateGameData);
        }
    }, {
        key: "showDamageOptions",
        value: function showDamageOptions(positionId) {
            actions = this.state.actions;
            actions.selectedPosition = positionId;
            this.setState({ actions: actions });
        }
    }, {
        key: "render",
        value: function render() {
            var _this2 = this;

            return React.createElement(
                "div",
                { id: "board_parent" },
                React.createElement(
                    "div",
                    { className: "overflow_helper" },
                    React.createElement(
                        "div",
                        { id: "board", style: { width: this.zooms[this.state.boardZoom] } },
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
                    { className: "slide_panel_stack_top" },
                    React.createElement(
                        SlidePanel,
                        { showText: "zoom" },
                        React.createElement(ZoomPanel, { onRefresh: this.update,
                            onZoomOut: this.updateBoardZoom.bind(this, -1),
                            onZoomIn: this.updateBoardZoom.bind(this, 1)
                        })
                    ),
                    React.createElement(
                        SlidePanel,
                        null,
                        React.createElement(RefreshPanel, { paused: this.state.refresher == null,
                            onPlayPause: this.changeRefresh })
                    )
                ),
                React.createElement(
                    SlidePanelStack,
                    { className: "slide_panel_stack_bottom" },
                    React.createElement(
                        SlidePanel,
                        { showText: "cars stats" },
                        React.createElement(CarDamagePanel, { cars: this.state.cars || [], users: this.state.users })
                    ),
                    this.state.actions != undefined && this.state.actions.type == "choose_gear" && React.createElement(
                        SlidePanel,
                        null,
                        React.createElement(GearChoicePanel, { current: this.state.actions.current_gear,
                            available: this.state.actions.available_gears,
                            onChooseGear: this.chooseGear })
                    ),
                    this.state.actions != undefined && this.state.actions.selectedPosition != null && React.createElement(
                        SlidePanel,
                        null,
                        React.createElement(MoveDamageSelector, { positionId: this.state.actions.selectedPosition,
                            moveOptions: this.state.actions.available_moves.filter(function (move) {
                                return move.fo_position_id == _this2.state.actions.selectedPosition;
                            }) })
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
        key: "render",
        value: function render() {
            alert(JSON.stringify(this.props.moveOptions)); //TODO: render the damage table; as props receive ONLY RELEVANT damages with corresponding MoveOptionId
            return React.createElement(
                "table",
                { id: "damage_table_" + this.props.positionId,
                    className: "move_option_damage damage_table" },
                React.createElement(
                    "tbody",
                    null,
                    this.props.moveOptions.map(function (moveOption) {
                        return React.createElement(
                            "tr",
                            { key: moveOption.fo_move_option_id },
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
        key: "render",
        value: function render() {
            var _this5 = this;

            availableMovesPositionIds = Array.from(new Set(this.props.availableMoves.map(function (move) {
                return move.fo_position_id;
            })));
            return React.createElement(
                "svg",
                { id: "formula_board", className: "board__svg" },
                availableMovesPositionIds.map(function (positionId) {
                    return React.createElement("circle", { key: positionId,
                        id: "move_position_" + positionId, className: "move_option",
                        cx: _this5.props.positions[positionId].x / 1000 + "%",
                        cy: _this5.props.positions[positionId].y / 1000 + "%",
                        r: ".8%", fill: "purple",
                        onClick: function onClick() {
                            return _this5.props.onMovePositionSelected(positionId);
                        } });
                })
            );
        }
    }]);

    return AvailableMovesSelectorOverlay;
}(React.Component);

var GearSelector = function (_React$Component4) {
    _inherits(GearSelector, _React$Component4);

    function GearSelector(props) {
        _classCallCheck(this, GearSelector);

        var _this6 = _possibleConstructorReturn(this, (GearSelector.__proto__ || Object.getPrototypeOf(GearSelector)).call(this, props));

        _this6.gearPositions = [{ x: 191, y: 144 }, { x: 191, y: 457 }, { x: 300, y: 144 }, { x: 300, y: 457 }, { x: 412, y: 144 }, { x: 412, y: 457 }];

        _this6.handleMouseEnter = _this6.handleMouseEnter.bind(_this6);
        _this6.handleMouseLeave = _this6.handleMouseLeave.bind(_this6);
        _this6.handleMouseClick = _this6.handleMouseClick.bind(_this6);
        _this6.state = {};
        return _this6;
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

var GearChoicePanel = function (_React$Component5) {
    _inherits(GearChoicePanel, _React$Component5);

    function GearChoicePanel() {
        _classCallCheck(this, GearChoicePanel);

        return _possibleConstructorReturn(this, (GearChoicePanel.__proto__ || Object.getPrototypeOf(GearChoicePanel)).apply(this, arguments));
    }

    _createClass(GearChoicePanel, [{
        key: "render",
        value: function render() {
            var _this8 = this;

            console.log(JSON.stringify(this.props.available.filter(function (gear) {
                return gear != _this8.props.current;
            })));
            return React.createElement(
                "div",
                { style: { position: "relative" } },
                React.createElement("img", { src: "/img/formula/gearbox.svg", className: "gear_choice",
                    width: "100px", height: "100px" }),
                React.createElement(
                    "svg",
                    { viewBox: "0 0 600 600", width: "100", height: "100",
                        style: { position: "absolute", top: 0, left: 0 } },
                    this.props.available.map(function (gear) {
                        return React.createElement(GearSelector, { key: gear, gear: gear,
                            color: gear == _this8.props.current ? "green" : "red",
                            onClick: function onClick() {
                                return _this8.props.onChooseGear(gear);
                            } });
                    })
                )
            );
        }
    }]);

    return GearChoicePanel;
}(React.Component);

var damageTypeClass = ["tires", "gearbox", "brakes", "engine", "chassis", "shocks"];

var DamagePanel = function (_React$Component6) {
    _inherits(DamagePanel, _React$Component6);

    function DamagePanel() {
        _classCallCheck(this, DamagePanel);

        return _possibleConstructorReturn(this, (DamagePanel.__proto__ || Object.getPrototypeOf(DamagePanel)).apply(this, arguments));
    }

    _createClass(DamagePanel, [{
        key: "render",
        value: function render() {
            return React.createElement(
                React.Fragment,
                null,
                this.props.damages.map(function (damage) {
                    return React.createElement(
                        "td",
                        { key: damage.type,
                            className: "damage " + damageTypeClass[damage.type - 1] },
                        damage.wear_points
                    );
                })
            );
        }
    }]);

    return DamagePanel;
}(React.Component);

var CarDamagePanel = function (_React$Component7) {
    _inherits(CarDamagePanel, _React$Component7);

    function CarDamagePanel() {
        _classCallCheck(this, CarDamagePanel);

        return _possibleConstructorReturn(this, (CarDamagePanel.__proto__ || Object.getPrototypeOf(CarDamagePanel)).apply(this, arguments));
    }

    _createClass(CarDamagePanel, [{
        key: "render",
        value: function render() {
            var _this11 = this;

            return React.createElement(
                "table",
                { id: "car_stats_table", className: "damage_table" },
                React.createElement(
                    "tbody",
                    null,
                    this.props.cars.map(function (car) {
                        return React.createElement(
                            "tr",
                            { key: car.index },
                            React.createElement(
                                "td",
                                null,
                                React.createElement(Sprite, { src: "/img/formula/cars/" + carSprites[car.index],
                                    className: "car_img", key: car.index,
                                    width: "20", height: "50", unit: "px" })
                            ),
                            React.createElement(
                                "td",
                                null,
                                _this11.props.users.find(function (user) {
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

var RefreshPanel = function (_React$Component8) {
    _inherits(RefreshPanel, _React$Component8);

    function RefreshPanel() {
        _classCallCheck(this, RefreshPanel);

        return _possibleConstructorReturn(this, (RefreshPanel.__proto__ || Object.getPrototypeOf(RefreshPanel)).apply(this, arguments));
    }

    _createClass(RefreshPanel, [{
        key: "render",
        value: function render() {
            return React.createElement(
                React.Fragment,
                null,
                React.createElement(
                    "button",
                    { onClick: this.props.onPlayPause },
                    this.props.paused ? "resume" : "pause"
                )
            );
        }
    }]);

    return RefreshPanel;
}(React.Component);

var ZoomPanel = function (_React$Component9) {
    _inherits(ZoomPanel, _React$Component9);

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
                    "Refresh"
                ),
                React.createElement(
                    "button",
                    { onClick: this.props.onZoomIn },
                    "+"
                ),
                React.createElement(
                    "button",
                    { onClick: this.props.onZoomOut },
                    "-"
                )
            );
        }
    }]);

    return ZoomPanel;
}(React.Component);

var SlidePanelStack = function (_React$Component10) {
    _inherits(SlidePanelStack, _React$Component10);

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

var SlidePanel = function (_React$Component11) {
    _inherits(SlidePanel, _React$Component11);

    function SlidePanel(props) {
        _classCallCheck(this, SlidePanel);

        var _this15 = _possibleConstructorReturn(this, (SlidePanel.__proto__ || Object.getPrototypeOf(SlidePanel)).call(this, props));

        _this15.state = { visible: true };
        _this15.toggleHide = _this15.toggleHide.bind(_this15);
        //this.onToggleHide = props.onToggleHide || (arg => {});
        return _this15;
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
                        this.state.visible ? "Hide" : this.props.showText || "Show"
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

var TrackImage = function (_React$Component12) {
    _inherits(TrackImage, _React$Component12);

    function TrackImage() {
        _classCallCheck(this, TrackImage);

        return _possibleConstructorReturn(this, (TrackImage.__proto__ || Object.getPrototypeOf(TrackImage)).apply(this, arguments));
    }

    _createClass(TrackImage, [{
        key: "render",
        value: function render() {
            return React.createElement("img", { className: "board__track", src: this.props.src });
        }
    }]);

    return TrackImage;
}(React.Component);

var carSprites = ["tdrc01_car01_b.png", "tdrc01_car01_e.png", "tdrc01_car01_f.png", "tdrc01_car03_a.png", "tdrc01_car03_c.png", "tdrc01_car03_d.png", "tdrc01_car04_a.png", "tdrc01_car04_d.png", "tdrc01_car04_f.png", "tdrc01_car07_b.png", "tdrc01_car07_d.png", "tdrc01_car07_f.png"];

var TrackCars = function (_React$Component13) {
    _inherits(TrackCars, _React$Component13);

    function TrackCars() {
        _classCallCheck(this, TrackCars);

        return _possibleConstructorReturn(this, (TrackCars.__proto__ || Object.getPrototypeOf(TrackCars)).apply(this, arguments));
    }

    _createClass(TrackCars, [{
        key: "render",
        value: function render() {
            var _this18 = this;

            if (this.props.cars == null) {
                return null;
            } else {
                return React.createElement(
                    React.Fragment,
                    null,
                    this.props.cars.map(function (car) {
                        return React.createElement(Sprite, { src: "/img/formula/cars/" + carSprites[car.index],
                            className: "car_img",
                            key: car.index,
                            x: _this18.props.positions[car.fo_position_id].x / 1000,
                            y: _this18.props.positions[car.fo_position_id].y / 1000,
                            angle: _this18.props.positions[car.fo_position_id].angle * 180 / Math.PI - 90
                        });
                    })
                );
            }
        }
    }]);

    return TrackCars;
}(React.Component);

var TrackDebris = function (_React$Component14) {
    _inherits(TrackDebris, _React$Component14);

    function TrackDebris() {
        _classCallCheck(this, TrackDebris);

        return _possibleConstructorReturn(this, (TrackDebris.__proto__ || Object.getPrototypeOf(TrackDebris)).apply(this, arguments));
    }

    _createClass(TrackDebris, [{
        key: "render",
        value: function render() {
            var _this20 = this;

            if (this.props.debris == null) {
                return null;
            } else {
                return React.createElement(
                    React.Fragment,
                    null,
                    this.props.debris.map(function (item) {
                        return React.createElement(Sprite, { src: "/img/formula/track-objects/oil.png",
                            className: "debris_img",
                            key: item.id,
                            x: _this20.props.positions[item.fo_position_id].x / 1000,
                            y: _this20.props.positions[item.fo_position_id].y / 1000,
                            angle: _this20.props.positions[item.fo_position_id].angle * 180 / Math.PI - 90
                        });
                    })
                );
            }
        }
    }]);

    return TrackDebris;
}(React.Component);

var Sprite = function (_React$Component15) {
    _inherits(Sprite, _React$Component15);

    function Sprite() {
        _classCallCheck(this, Sprite);

        return _possibleConstructorReturn(this, (Sprite.__proto__ || Object.getPrototypeOf(Sprite)).apply(this, arguments));
    }

    _createClass(Sprite, [{
        key: "render",
        value: function render() {
            var width = this.props.width || .8;
            var height = this.props.height || 2;
            var unit = this.props.unit || "%";
            return React.createElement("img", { src: this.props.src,
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