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

class Board extends React.Component {
    constructor(props) {
        super(props);
        this.state = {boardZoom: 0};
        this.update = this.update.bind(this);
        this.updateGameData = this.updateGameData.bind(this);
        this.update();  //TODO: run this after the document loaded
        this.changeRefresh = this.changeRefresh.bind(this);
        this.chooseGear = this.chooseGear.bind(this);
        this.showDamageOptions = this.showDamageOptions.bind(this);
        this.chooseMoveOption = this.chooseMoveOption.bind(this);
    }
    
    zooms = ["100%", "150%", "200%", "250%", "300%"];
    
    changeRefresh() {
        if (this.state.refresher != null) {
            clearInterval(this.state.refresher);
            this.setState({refresher: null});
        } else {
            this.setState({refresher: setInterval(this.update, 1000)});
        }
    }
    
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
                modified: data.modified,
            });
        }
    }
    
    chooseGear(gear) {
        $.post('/formula/chooseGear/' + this.props.id,
            { _csrfToken: csrfToken, game_id: this.props.id, gear: gear },
            this.update,
            "json");
        console.log("chooseGear(" + gear + ")");
    }
    
    update() {
        $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateGameData);
    }
    
    showDamageOptions(positionId) {
        this.setState({selectedPosition: positionId});
    }
    
    chooseMoveOption(moveOptionId) {
        this.setState({selectedPosition: null});
        $.post('/formula/chooseMoveOption/' + this.props.id,
                { _csrfToken: csrfToken, game_id: this.props.id, move_option_id: moveOptionId },
                this.update,
                "json");
        console.log("chooseMoveOption(" + moveOptionId + ")");
    }
    
    render() {
        return (
          <div id="board_parent">
            <div className="overflow_helper">
              <div id="board" style={{width: this.zooms[this.state.boardZoom]}}>
                <TrackImage src={this.props.gameBoard}></TrackImage>
                {console.log(JSON.stringify(this.state.actions))}
                {this.state.actions != undefined && this.state.actions.type == "choose_move" &&
                  <AvailableMovesSelectorOverlay
                    availableMoves={this.state.actions.available_moves}
                    positions={this.props.positions}
                    onMovePositionSelected={this.showDamageOptions}/>
                }
                <TrackCars cars={(this.state.cars || []).filter(car => car.fo_position_id != null)}
                  positions={this.props.positions} />
                <TrackDebris debris={this.state.trackDebris || []} positions={this.props.positions} />
              </div>
            </div>
            <SlidePanelStack className="slide_panel_stack_top">
              <SlidePanel showIcon="/img/formula/downarrow.svg"
                hideIcon="/img/formula/uparrow.svg">
                  <ZoomPanel onRefresh={this.update}
                    noZoomIn={this.state.boardZoom == this.zooms.length - 1}
                    noZoomOut={this.state.boardZoom == 0}
                    onZoomOut={this.updateBoardZoom.bind(this, -1)}
                    onZoomIn={this.updateBoardZoom.bind(this, 1)}
                    />
              </SlidePanel>
              <SlidePanel showIcon="/img/formula/downarrow.svg"
                hideIcon="/img/formula/uparrow.svg">
                  <RefreshPanel paused={this.state.refresher == null}
                    onPlayPause={this.changeRefresh} />
              </SlidePanel>
            </SlidePanelStack>
            <SlidePanelStack className="slide_panel_stack_bottom">
              {this.state.actions != undefined && this.state.actions.type == "choose_gear" &&
                <SlidePanel showIcon="/img/formula/uparrow.svg"
                  hideIcon="/img/formula/downarrow.svg">
                    <GearChoicePanel current={this.state.actions.current_gear}
                      available={this.state.actions.available_gears}
                      onChooseGear={this.chooseGear} />
                </SlidePanel> 
              }
              {this.state.selectedPosition != null &&
                <SlidePanel showIcon="/img/formula/uparrow.svg"
                  hideIcon="/img/formula/downarrow.svg">
                    <MoveDamageSelector positionId={this.state.selectedPosition}
                      onSelected={this.chooseMoveOption}
                      moveOptions={
                        this.state.actions.available_moves.filter(move =>
                          move.fo_position_id == this.state.selectedPosition)} />
                </SlidePanel>
              }
              {this.state.actions != undefined && this.state.actions.type == "choose_pits" &&
                <SlidePanel showIcon="/img/formula/uparrow.svg"
                  hideIcon="/img/formula/downarrow.svg">
                  <PitStopPanel car={this.state.cars.filter(car => car.state == "R").sort(car => car.order)[0]}
                    availablePoints={this.state.actions.available_points}
                    maxPoints={this.state.actions.max_points} />
                </SlidePanel>
              }
              <SlidePanel showText="cars stats"
                hideIcon="/img/formula/downarrow.svg">
                  <CarDamagePanel update={Math.random()}
                    cars={this.state.cars || []} users={this.state.users} />
              </SlidePanel>
            </SlidePanelStack>
          </div>
        );
    }
}

ReactDOM.render(<Board id={id} gameBoard={gameBoard} positions={positions} />, document.getElementById('root'));


