<?php
namespace App\Http\Controllers;
use App\Models\Job_Offer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Log;
use App\Models\User;
use App\Services\User\EmployeeService;
use App\Services\User\UserService;

class EmployeeController extends Controller
{
        private $employeeService;

        /**
         * Inject UserService into the Controller.
         *
         * @param EmployeeService $employeeService
         */
        public function __construct(EmployeeService $employeeService)
        {
            $this->employeeService = $employeeService;
        }

        /**
         * Handle the login request.
         *
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */




         public function store(StoreEmployeeRequest $request)
{
    $response = $this->employeeService->addEmployee(
        $request->only(['name']),
        $request->only(['salary', 'position'])
    );

    if ($response['success']) {
        return response()->json($response, 201);
    }

    return response()->json($response, 500);
}
public function updateJobDetails(Request $request, int $employeeId)
{
    $response = $this->employeeService->updateJobDetails(
        $employeeId,
        $request->only(['salary', 'position'])
    );

    return response()->json($response, $response['success'] ? 200 : 400);
}
public function destroy(int $employeeId)
{
    $response = $this->employeeService->deleteEmployee($employeeId);
    return response()->json($response, $response['success'] ? 200 : 400);
}

// app/Http/Controllers/EmployeeController.php
public function getByPosition(Request $request)
{
    $response = $this->employeeService->getEmployeesByPosition($request->query('position'));
    return response()->json($response, $response['success'] ? 200 : 400);
}

  public function index(){

    return User::with('jobOffer')->where('position', '=','employee')->get();

  }

  // app/Http/Controllers/EmployeeController.php
public function getByName(Request $request)
{
    $response = $this->employeeService->getEmployeesByName($request->query('name'));
    return response()->json($response, $response['success'] ? 200 : 400);
}
}
