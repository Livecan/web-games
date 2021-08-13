DELETE FROM `fo_position2positions` WHERE EXISTS
(SELECT 1 FROM `fo_positions` pos_1
 WHERE pos_1.id = fo_position_from_id
 AND pos_1.fo_track_id = 1
 AND pos_1.order = 81
 AND is_left)