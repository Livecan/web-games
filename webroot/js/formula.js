var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/* 
 * Formula Game React App
 */

import { SlidePanel, SlidePanelStack } from './module/slidePanel.js';
import { PitStopPanel } from './formula/pitStopPanel.js';
import { GearChoicePanel } from './formula/gearChoicePanel.js';
import { TrackImage } from './formula/trackImage.js';
import { TrackCars } from './formula/trackCars.js';
import { TrackDebris } from './formula/trackDebris.js';
import { ZoomPanel } from './formula/zoomPanel.js';
import { RefreshPanel } from './formula/refreshPanel.js';
import { CarDamagePanel } from './formula/carDamagePanel.js';
import { AvailableMovesSelectorOverlay } from './formula/availableMovesSelectorOverlay.js';
import { MoveDamageSelector } from './formula/moveDamageSelector.js';
import { Tooltip } from './module/tooltip.js';

var Board = function (_React$Component) {
  _inherits(Board, _React$Component);

  function Board(props) {
    _classCallCheck(this, Board);

    var _this = _possibleConstructorReturn(this, (Board.__proto__ || Object.getPrototypeOf(Board)).call(this, props));

    _this.refreshInterval = 2000;
    _this.zooms = ["100%", "150%", "200%", "250%", "300%"];

    _this.state = {};
    _this.state.boardZoom = 0;
    _this.updateModified = _this.update.bind(_this, true);
    _this.update = _this.update.bind(_this, false);
    _this.updateGameData = _this.updateGameData.bind(_this);
    _this.update(); //TODO: run this after the document loaded
    _this.state.refresher = setInterval(_this.updateModified, _this.refreshInterval);
    _this.chooseGear = _this.chooseGear.bind(_this);
    _this.showDamageOptions = _this.showDamageOptions.bind(_this);
    _this.chooseMoveOption = _this.chooseMoveOption.bind(_this);
    _this.displayTooltip = _this.displayTooltip.bind(_this);
    _this.hideTooltip = _this.hideTooltip.bind(_this);
    return _this;
  }

  _createClass(Board, [{
    key: 'updateBoardZoom',


    /*changeRefresh() {
        if (this.state.refresher != null) {
            clearInterval(this.state.refresher);
            this.setState({refresher: null});
        } else {
            this.setState({refresher: setInterval(this.updateModified, this.refreshInterval)});
        }
    }*/

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
          actions: data.actions,
          modified: data.modified
        });
      }
    }
  }, {
    key: 'chooseGear',
    value: function chooseGear(gear) {
      $.post('formula/chooseGear/' + this.props.id, { _csrfToken: csrfToken, game_id: this.props.id, gear: gear }, this.update, "json");
    }
  }, {
    key: 'update',
    value: function update(sendModified) {
      var url = 'formula/getBoardUpdateJson/' + this.props.id;
      $.getJSON(url, { modified: sendModified ? this.state.modified : null }, this.updateGameData);
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
      $.post('formula/chooseMoveOption/' + this.props.id, { _csrfToken: csrfToken, game_id: this.props.id, move_option_id: moveOptionId }, this.update, "json");
    }
  }, {
    key: 'displayTooltip',
    value: function displayTooltip(id, x, y, text) {
      this.setState({ tooltip: { id: id, x: x, y: y, text: text } });
    }
  }, {
    key: 'hideTooltip',
    value: function hideTooltip(id) {
      if (this.state.tooltip != null && this.state.tooltip.id == id) {
        this.setState({ tooltip: null });
      }
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
            this.state.actions != undefined && this.state.actions.type == "choose_move" && React.createElement(AvailableMovesSelectorOverlay, {
              availableMoves: this.state.actions.available_moves,
              positions: this.props.positions,
              onMovePositionSelected: this.showDamageOptions }),
            React.createElement(TrackCars, { cars: (this.state.cars || []).filter(function (car) {
                return car.fo_position_id != null;
              }),
              positions: this.props.positions }),
            React.createElement(TrackDebris, { debris: this.state.trackDebris, positions: this.props.positions })
          )
        ),
        React.createElement(
          SlidePanelStack,
          { className: 'slide_panel_stack_top' },
          React.createElement(
            SlidePanel,
            { showIcon: 'img/formula/downarrow.svg',
              hideIcon: 'img/formula/uparrow.svg' },
            React.createElement(ZoomPanel, { onRefresh: this.update,
              noZoomIn: this.state.boardZoom == this.zooms.length - 1,
              noZoomOut: this.state.boardZoom == 0,
              onZoomOut: this.updateBoardZoom.bind(this, -1),
              onZoomIn: this.updateBoardZoom.bind(this, 1)
            })
          ),
          false && React.createElement(
            SlidePanel,
            { showIcon: 'img/formula/downarrow.svg',
              hideIcon: 'img/formula/uparrow.svg' },
            React.createElement(RefreshPanel, { paused: this.state.refresher == null,
              onPlayPause: this.changeRefresh })
          )
        ),
        React.createElement(
          SlidePanelStack,
          { className: 'slide_panel_stack_bottom' },
          this.state.actions != undefined && this.state.actions.type == "choose_gear" && React.createElement(
            SlidePanel,
            { showIcon: 'img/formula/uparrow.svg',
              hideIcon: 'img/formula/downarrow.svg' },
            React.createElement(GearChoicePanel, { current: this.state.actions.current_gear,
              available: this.state.actions.available_gears,
              onChooseGear: this.chooseGear,
              onDisplayTooltip: this.displayTooltip,
              onHideTooltip: this.hideTooltip })
          ),
          this.state.selectedPosition != null && React.createElement(
            SlidePanel,
            { showIcon: 'img/formula/uparrow.svg',
              hideIcon: 'img/formula/downarrow.svg' },
            React.createElement(MoveDamageSelector, { positionId: this.state.selectedPosition,
              onSelected: this.chooseMoveOption,
              moveOptions: this.state.actions.available_moves.filter(function (move) {
                return move.fo_position_id == _this2.state.selectedPosition;
              }) })
          ),
          this.state.actions != undefined && this.state.actions.type == "choose_pits" && React.createElement(
            SlidePanel,
            { showIcon: 'img/formula/uparrow.svg',
              hideIcon: 'img/formula/downarrow.svg' },
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
              hideIcon: 'img/formula/downarrow.svg' },
            React.createElement(CarDamagePanel, { update: Math.random(),
              cars: this.state.cars || [], users: this.state.users })
          )
        ),
        this.state.tooltip != null && React.createElement(
          Tooltip,
          { x: this.state.tooltip.x, y: this.state.tooltip.y },
          this.state.tooltip.text
        )
      );
    }
  }]);

  return Board;
}(React.Component);

ReactDOM.render(React.createElement(Board, { id: id, gameBoard: gameBoard, positions: positions }), document.getElementById('root'));