<?php


namespace reunionou\services;

use reunionou\models\Event;

class EventService {

    public static function getEventById(int $id): ?array
    {
        $event = Event::select('id', 'title', 'description', 'latitude', 'longitude', 'street', 'zipcode', 'city', "organizer_id", "created_at")
        ->where('id', '=', $id)
        ->first();
    
        return $event ? $event->toArray() : null;
    }
    
    public static function getAllEvents(): ?array
    {
        $events = Event::select('id', 'title', 'description', 'latitude', 'longitude', 'street', 'zipcode', 'city', "organizer_id", "created_at")
        ->get();
    
        return $events ? $events->toArray() : null;
    }

    public static function deleteEvent(int $event_id, int $user_id): ?array
    {
        $event = Event::find($event_id);
    
        if (!$event) {
            return ['error' => 'Event not found'];
        }
    

        if ($event->organizer_id != $user_id) {
            return ['error' => 'User is not the organizer of the event'];
        }
    
        $event->delete();
        return [];
    }

    public static function getEventsUserParticipated(int $user_id): ?array
    {
        try {
            $events = Event::select('events.id', 'title', 'description', 'latitude', 'longitude', 'street', 'zipcode', 'city', "organizer_id", "events.created_at")
            ->join('participants', 'participants.event_id', '=', 'events.id')
            ->where('participants.user_id', '=', $user_id)
            ->distinct()
            ->get();
    
            return $events ? $events->toArray() : null;
        } catch (\Exception $e) {
            error_log('Failed to get events for participant: ' . $e->getMessage());
            return ['error' => 'Failed to get events for participant'];
        }
    }
    

    public static function getCreatedEventsByUserId(int $user_id): ?array
    {
        $events = Event::select('id', 'title', 'description', 'latitude', 'longitude', 'street', 'zipcode', 'city', "organizer_id", "created_at")
            ->where('organizer_id', '=', $user_id)
            ->get();

        return $events ? $events->toArray() : null;
    }

    public static function createEvent(string $title, string $description, string $street, string $zipcode, string $city, int $organize_id): array
    {
            $event = new Event();
            $event->title = $title;
            $event->description = $description;
            $event->street = $street;
            $event->zipcode = $zipcode;
            $event->city = $city;
            $event->organizer_id = $organize_id;
            $event->save();
    
            return $event->toArray();
    }
}