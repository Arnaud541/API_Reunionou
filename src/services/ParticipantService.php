<?php


namespace reunionou\services;

use reunionou\models\Participant;

class ParticipantService
{

    public static function getParticipantsByEventId(int $event_id): ?array
    {
        $participants = Participant::select('id', 'event_id', 'user_id', 'firstname', 'lastname', 'email', 'status', 'created_at')
            ->where('event_id', '=', $event_id)
            ->get();

        return $participants ? $participants->toArray() : null;
    }

    public static function inviteParticipant(int $event_id, int $user_id, string $firstname, string $lastname, string $email, string $status): ?array
    {

        if (empty($event_id) || empty($user_id)) {
            return ['error' => 'Missing required fields'];
        }

        $existingParticipant = Participant::where('email', '=', $email)->first();
        if ($existingParticipant !== null)
            return ['error' => 'User was already invited'];

        $participant = new Participant;
        $participant->event_id = $event_id;
        $participant->participant_id = $user_id;
        $participant->firstname = $firstname;
        $participant->lastname = $lastname;
        $participant->email = $email;
        $participant->status = $status;
        $participant->save();

        return $participant->toArray();
    }

    public static function updateParticipantStatus(int $user_id, string $status): array
    {
        $participant = Participant::where('user_id', '=', $user_id)->first();

        if (!$participant) {
            return ['error' => 'Participant not found'];
        }

        try {
            $participant->status = $status;
            $participant->save();

            return $participant->toArray();
        } catch (\Exception $e) {
            return ['error' => 'Failed to update participant status'];
        }
    }
}
