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
        this.getUpdateModified = this.getUpdate.bind(this, true);
        this.getUpdate = this.getUpdate.bind(this, false);
        this.updateGameData = this.updateGameData.bind(this);
        this.getUpdate();  //TODO: run this after the document loaded
        this.state.refresher = setInterval(this.getUpdateModified, this.refreshInterval);
        this.postChooseGear = this.postChooseGear.bind(this);
        this.showDamageOptions = this.showDamageOptions.bind(this);
        this.postChooseMoveOption = this.postChooseMoveOption.bind(this);
        this.postChoosePitsFix = this.postChoosePitsFix.bind(this);
        this.displayTooltip = this.displayTooltip.bind(this);
        this.hideTooltip = this.hideTooltip.bind(this);
    }

    zooms = ["100%", "150%", "200%", "250%", "300%"];

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
                cars: data.fo_cars.map((car, index) =>
                    {
                        car.index = index;
                        return car;
                    }
                ),
                users: data.users,
                logs: data.fo_logs, //TODO: refactor/use it in a nice UI element
                actions: data.actions,
                modified: data.modified,
            });
        }
    }

    postChooseGear(gear) {
        $.post('formula/chooseGear/' + this.props.id,
            { _csrfToken: csrfToken, game_id: this.props.id, gear: gear },
            this.getUpdate,
            "json");
    }

    getUpdate(sendModified) {
        let url = 'formula/getBoardUpdateJson/' + this.props.id;
        $.getJSON(url, {modified: sendModified ? this.state.modified : null}, this.updateGameData);
    }

    showDamageOptions(positionId) {
        this.setState({actions:
            {
                ...this.state.actions,
                selectedPosition: positionId
            }
        });
    }

    postChooseMoveOption(moveOptionId) {
        this.setState({actions:
            {
                ...this.state.actions,
                selectedPosition: null
            }
        });
        $.post('formula/chooseMoveOption/' + this.props.id,
                { _csrfToken: csrfToken, game_id: this.props.id, move_option_id: moveOptionId },
                this.getUpdate,
                "json");
    }

    postChoosePitsFix(tires, fixes) {
      $.post('formula/choosePitsOptions/' + this.props.id,
      { _csrfToken: csrfToken, game_id: this.props.id, tires: tires, fixes: fixes },
      this.getUpdate,
      "json");
    }

    displayTooltip(id, x, y, text) {
        this.setState({tooltip: {id: id, x: x, y: y, text: text}});
    }

    hideTooltip(id) {
        if (this.state.tooltip?.id == id) {
            this.setState({tooltip: null});
        }
    }

    render() {
        return (
          <div id="board_parent">
            <div className="overflow_helper">
              <div id="board" style={{width: this.zooms[this.state.boardZoom]}}>
                <TrackImage src={this.props.gameBoard}></TrackImage>
                {this.state.actions?.type == "choose_move" &&
                  <AvailableMovesSelectorOverlay
                    availableMoves={this.state.actions.available_moves}
                    positions={this.props.positions}
                    selectedPositionId={this.state.actions?.selectedPosition}
                    onMovePositionSelected={this.showDamageOptions}
                    onSelected={this.postChooseMoveOption}/>
                }
                <TrackCars cars={this.state.cars?.filter(car => car.fo_position_id != null) ?? []}
                  positions={this.props.positions} />
                <TrackDebris debris={this.state.trackDebris} positions={this.props.positions} />
              </div>
            </div>
            <SlidePanelStack className="slide_panel_stack_top">
              <SlidePanel showIcon="img/formula/downarrow.svg"
                hideIcon="img/formula/uparrow.svg">
                  <ZoomPanel onRefresh={this.getUpdate}
                    noZoomIn={this.state.boardZoom == this.zooms.length - 1}
                    noZoomOut={this.state.boardZoom == 0}
                    onZoomOut={this.updateBoardZoom.bind(this, -1)}
                    onZoomIn={this.updateBoardZoom.bind(this, 1)}
                    />
              </SlidePanel>
            </SlidePanelStack>
            <SlidePanelStack className="slide_panel_stack_bottom">
              {this.state.actions?.type == "choose_gear" &&
                <SlidePanel showIcon="img/formula/uparrow.svg"
                  hideIcon="img/formula/downarrow.svg">
                    <GearChoicePanel current={this.state.actions.current_gear}
                      available={this.state.actions.available_gears}
                      onChooseGear={this.postChooseGear}
                      onDisplayTooltip={this.displayTooltip}
                      onHideTooltip={this.hideTooltip}/>
                </SlidePanel>
              }
              {this.state.actions?.selectedPosition != null &&
                <SlidePanel showIcon="img/formula/uparrow.svg"
                  hideIcon="img/formula/downarrow.svg">
                    <MoveDamageSelector positionId={this.state.actions.selectedPosition}
                      onSelected={this.postChooseMoveOption}
                      moveOptions={
                        this.state.actions.available_moves.filter(move =>
                          move.fo_position_id == this.state.actions.selectedPosition)} />
                </SlidePanel>
              }
              {this.state.actions?.type == "choose_pits" &&
                <SlidePanel showIcon="img/formula/uparrow.svg"
                  hideIcon="img/formula/downarrow.svg">
                  <PitStopPanel car={this.state.cars.find(car => car.id == this.state.actions.car_id)}
                    availablePoints={this.state.actions.available_points}
                    maxPoints={this.state.actions.max_points}
                    onPitStopSelected={this.postChoosePitsFix} />
                </SlidePanel>
              }
              <SlidePanel showText="cars stats"
                hideIcon="img/formula/downarrow.svg">
                  <CarDamagePanel cars={this.state.cars ?? []} users={this.state.users} />
              </SlidePanel>
            </SlidePanelStack>
            {
              this.state.tooltip != null &&
              <Tooltip x={this.state.tooltip.x} y={this.state.tooltip.y}>
                {this.state.tooltip.text}
              </Tooltip>
            }
          </div>
        );
    }
}

ReactDOM.render(<Board id={id} gameBoard={gameBoard} positions={positions} />, document.getElementById('root'));
