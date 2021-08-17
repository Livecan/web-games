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

class Board extends React.Component {
  refreshInterval = 2000;

  constructor(props) {
    super(props);
    this.state = {};
    this.state.boardZoom = 0;
    this.updateModified = this.update.bind(this, true);
    this.update = this.update.bind(this, false);
    this.updateGameData = this.updateGameData.bind(this);
    this.update(); //TODO: run this after the document loaded

    this.state.refresher = setInterval(this.updateModified, this.refreshInterval);
    this.chooseGear = this.chooseGear.bind(this);
    this.showDamageOptions = this.showDamageOptions.bind(this);
    this.chooseMoveOption = this.chooseMoveOption.bind(this);
    this.displayTooltip = this.displayTooltip.bind(this);
    this.hideTooltip = this.hideTooltip.bind(this);
  }

  zooms = ["100%", "150%", "200%", "250%", "300%"];
  /*changeRefresh() {
      if (this.state.refresher != null) {
          clearInterval(this.state.refresher);
          this.setState({refresher: null});
      } else {
          this.setState({refresher: setInterval(this.updateModified, this.refreshInterval)});
      }
  }*/

  updateBoardZoom(zoom) {
    if (zoom > 0) {
      this.state.boardZoom = Math.min(this.state.boardZoom + 1, this.zooms.length - 1);
    }

    if (zoom < 0) {
      this.state.boardZoom = Math.max(this.state.boardZoom - 1, 0);
    }

    this.setState(this.state);
  }

  updateGameData(data) {
    if (data.redirect) {
      window.location.href = data.target + "?redirect=" + encodeURI('/formula/get_board/') + this.props.id;
    }

    if (data.has_updated) {
      this.setState({
        gameState: data.game_state_id,
        trackDebris: data.fo_debris,
        cars: data.fo_cars.map((car, index) => {
          car.index = index;
          return car;
        }),
        users: data.users,
        logs: data.fo_logs,
        //TODO: refactor/use it in a nice UI element
        actions: data.actions,
        modified: data.modified
      });
    }
  }

  chooseGear(gear) {
    $.post('formula/chooseGear/' + this.props.id, {
      _csrfToken: csrfToken,
      game_id: this.props.id,
      gear: gear
    }, this.update, "json");
  }

  update(sendModified) {
    let url = 'formula/getBoardUpdateJson/' + this.props.id;
    $.getJSON(url, {
      modified: sendModified ? this.state.modified : null
    }, this.updateGameData);
  }

  showDamageOptions(positionId) {
    this.setState({
      actions: { ...this.state.actions,
        selectedPosition: positionId
      }
    });
  }

  chooseMoveOption(moveOptionId) {
    this.setState({
      actions: { ...this.state.actions,
        selectedPosition: null
      }
    });
    $.post('formula/chooseMoveOption/' + this.props.id, {
      _csrfToken: csrfToken,
      game_id: this.props.id,
      move_option_id: moveOptionId
    }, this.update, "json");
  }

  displayTooltip(id, x, y, text) {
    this.setState({
      tooltip: {
        id: id,
        x: x,
        y: y,
        text: text
      }
    });
  }

  hideTooltip(id) {
    if (this.state.tooltip?.id == id) {
      this.setState({
        tooltip: null
      });
    }
  }

  render() {
    return /*#__PURE__*/React.createElement("div", {
      id: "board_parent"
    }, /*#__PURE__*/React.createElement("div", {
      className: "overflow_helper"
    }, /*#__PURE__*/React.createElement("div", {
      id: "board",
      style: {
        width: this.zooms[this.state.boardZoom]
      }
    }, /*#__PURE__*/React.createElement(TrackImage, {
      src: this.props.gameBoard
    }), this.state.actions?.type == "choose_move" && /*#__PURE__*/React.createElement(AvailableMovesSelectorOverlay, {
      availableMoves: this.state.actions.available_moves,
      positions: this.props.positions,
      selectedPositionId: this.state.actions?.selectedPosition,
      onMovePositionSelected: this.showDamageOptions,
      onSelected: this.chooseMoveOption
    }), /*#__PURE__*/React.createElement(TrackCars, {
      cars: this.state.cars?.filter(car => car.fo_position_id != null) ?? [],
      positions: this.props.positions
    }), /*#__PURE__*/React.createElement(TrackDebris, {
      debris: this.state.trackDebris,
      positions: this.props.positions
    }))), /*#__PURE__*/React.createElement(SlidePanelStack, {
      className: "slide_panel_stack_top"
    }, /*#__PURE__*/React.createElement(SlidePanel, {
      showIcon: "img/formula/downarrow.svg",
      hideIcon: "img/formula/uparrow.svg"
    }, /*#__PURE__*/React.createElement(ZoomPanel, {
      onRefresh: this.update,
      noZoomIn: this.state.boardZoom == this.zooms.length - 1,
      noZoomOut: this.state.boardZoom == 0,
      onZoomOut: this.updateBoardZoom.bind(this, -1),
      onZoomIn: this.updateBoardZoom.bind(this, 1)
    }))), /*#__PURE__*/React.createElement(SlidePanelStack, {
      className: "slide_panel_stack_bottom"
    }, this.state.actions?.type == "choose_gear" && /*#__PURE__*/React.createElement(SlidePanel, {
      showIcon: "img/formula/uparrow.svg",
      hideIcon: "img/formula/downarrow.svg"
    }, /*#__PURE__*/React.createElement(GearChoicePanel, {
      current: this.state.actions.current_gear,
      available: this.state.actions.available_gears,
      onChooseGear: this.chooseGear,
      onDisplayTooltip: this.displayTooltip,
      onHideTooltip: this.hideTooltip
    })), this.state.actions?.selectedPosition != null && /*#__PURE__*/React.createElement(SlidePanel, {
      showIcon: "img/formula/uparrow.svg",
      hideIcon: "img/formula/downarrow.svg"
    }, /*#__PURE__*/React.createElement(MoveDamageSelector, {
      positionId: this.state.actions.selectedPosition,
      onSelected: this.chooseMoveOption,
      moveOptions: this.state.actions.available_moves.filter(move => move.fo_position_id == this.state.actions.selectedPosition)
    })), this.state.actions?.type == "choose_pits" && /*#__PURE__*/React.createElement(SlidePanel, {
      showIcon: "img/formula/uparrow.svg",
      hideIcon: "img/formula/downarrow.svg"
    }, /*#__PURE__*/React.createElement(PitStopPanel, {
      car: this.state.cars.find(car => car.id == this.state.actions.car_id),
      availablePoints: this.state.actions.available_points,
      maxPoints: this.state.actions.max_points
    })), /*#__PURE__*/React.createElement(SlidePanel, {
      showText: "cars stats",
      hideIcon: "img/formula/downarrow.svg"
    }, /*#__PURE__*/React.createElement(CarDamagePanel, {
      update: Math.random(),
      cars: this.state.cars ?? [],
      users: this.state.users
    }))), this.state.tooltip != null && /*#__PURE__*/React.createElement(Tooltip, {
      x: this.state.tooltip.x,
      y: this.state.tooltip.y
    }, this.state.tooltip.text));
  }

}

ReactDOM.render( /*#__PURE__*/React.createElement(Board, {
  id: id,
  gameBoard: gameBoard,
  positions: positions
}), document.getElementById('root'));