export class AvailableMovesSelectorOverlay extends React.Component {
    render() {
        let availableMovesPositionIds = Array.from(new Set(this.props.availableMoves.map(move => move.fo_position_id)));
        return (
          <svg id="formula_board" className="board__svg">
            {availableMovesPositionIds.map(positionId =>
              <circle key={positionId}
                id={"move_position_" + positionId} className="move_option"
                cx={this.props.positions[positionId].x / 1000 + "%"}
                cy={this.props.positions[positionId].y / 1000 + "%"}
                r=".8%" fill="purple"
                onClick={() => this.props.onMovePositionSelected(positionId)} />
            )}
          </svg>
        );
    }
}
