<?php

namespace App\Repositories;

use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Models\FamilyMember;
use Exception;
use Illuminate\Support\Facades\DB;

class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
	public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ){
        $query = FamilyMember::with(['user'])
            ->where(function ($query) use ($search){
                //jika ada parameter search, maka akan mengambil data berdasarkan search
                if ($search) {
                    $query->search($search);
                }
            });

        if($limit) {
            //take adalah mengambil data beberapa berdasarkan limit
            $query->take($limit);
        };

        if($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ){
        $query = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ){
        $query = FamilyMember::with(['user', 'headOfFamily'])
            ->where('id', $id);

        return $query->first();
    }

    public function create(
        array $data
    ){
        DB::beginTransaction();

        try {
            $userRepository = new UserRepository;
            $user = $userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $familyMember = new FamilyMember;

            $familyMember->user_id = $user->id;
            $familyMember->head_of_family_id = $data['head_of_family_id'];
            $familyMember->identity_number = $data['identity_number'];
            $familyMember->gender = $data['gender'];
            $familyMember->birth_date = $data['birth_date'];
            $familyMember->phone_number = $data['phone_number'];
            $familyMember->occupation = $data['occupation'];
            $familyMember->marital_status = $data['marital_status'];
            $familyMember->relation = $data['relation'];

            if(isset($data['profile_picture'])){
                $familyMember->profile_picture = $data['profile_picture']->store('assets/family-member', 'public');
            }

            $familyMember->save();
            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ){
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);

            $familyMember->identity_number = $data['identity_number'];
            $familyMember->gender = $data['gender'];
            $familyMember->birth_date = $data['birth_date'];
            $familyMember->phone_number = $data['phone_number'];
            $familyMember->occupation = $data['occupation'];
            $familyMember->marital_status = $data['marital_status'];
            $familyMember->relation = $data['relation'];

            if(isset($data['profile_picture'])){
                $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members', 'public');
            }

            $familyMember->save();
            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function delete(
        string $id
    ){
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);

            $familyMember->delete();
            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
