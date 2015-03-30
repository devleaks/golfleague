create or replace view competition_registration
as
select	id,
		name,
		'COMPETITION' as event_type,
		'ACTIVE' as status,
		created_at,
		updated_at,
		'' as description,
		registration_begin as event_start,
		registration_end as event_end,
		'COMPETITION' as object_type,
		id as object_id
from competition