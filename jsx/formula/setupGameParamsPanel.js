export class SetupGameParamsPanel extends React.Component {
    render() {
        return (
            <table>
              <tbody>
                <tr>
                  <td>
                    <label htmlFor="track-choice">Track</label>
                  </td>
                  <td>
                    <select name="track-choice" id="track-choice">
                      <option value="1">Monaco</option>
                      <option value="2">Daytona</option>
                      //TODO: load these option from the server
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <img src={"img/formula/" + this.props.game.fo_game.fo_track.game_plan}
                        alt={this.props.game.fo_game.fo_track.game_plan} />
                  </td>
                </tr>
                <tr>
                  <td>
                    <label htmlFor="wear-points-available">WP</label>
                  </td>
                  <td>
                    <input type="number" id="wear-points-available"
                        name="wear-points-available" min="6"
                        defaultValue={this.props.game.fo_game.wear_points} />
                  </td>
                </tr>
                <tr>
                  <td>
                    <label htmlFor="laps">Laps</label>
                  </td>
                  <td>
                    <input type="number" id="laps" name="laps" min="1"
                        defaultValue={this.props.game.fo_game.laps} />
                  </td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <button>Start</button>
                  </td>
                </tr>
              </tbody>
            </table>
        )
    }
}