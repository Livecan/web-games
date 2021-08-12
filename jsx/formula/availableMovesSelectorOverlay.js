import { Sprite } from './sprite.js';

export class AvailableMovesSelectorOverlay extends React.Component {
    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }
    
    handleClick(positionId) {
        if (this.props.selectedPositionId == positionId) {
            let selectedMoves = this.props.availableMoves.filter(move => move.fo_position_id == positionId);
            if (selectedMoves.length == 1) {
                this.props.onSelected(selectedMoves[0].id);
            }
        } else {
            this.props.onMovePositionSelected(positionId);
        }
    }
    
    render() {
        let availableMovesPositionIds = Array.from(new Set(this.props.availableMoves.map(move => move.fo_position_id)));
        return (
          <React.Fragment>
            {availableMovesPositionIds.map(positionId =>
              <Sprite src={"img/formula/move-options/" +
                          (this.props.availableMoves.find(move => move.fo_position_id == positionId)
                            .fo_damages.every(damage => damage.wear_points == 0) ?
                          "car-outline-nodamage" :
                          "car-outline-damage") +
                          (this.props.selectedPositionId == positionId ? "-selected" : "") +
                          ".svg"
                        }
                    className="car_img"
                    key={positionId}
                    width="1.2%"
                    height="1.8%"
                    x={this.props.positions[positionId].x / 1000}
                    y={this.props.positions[positionId].y / 1000}
                    angle={this.props.positions[positionId].angle * 180 / Math.PI}
                    disappearOnMouseOver={false}
                    onClick={() => this.handleClick(positionId)}
              />
            )}
          </React.Fragment>
        );
    }
}
